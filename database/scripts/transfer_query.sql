
/* Uvoz korisnika */
INSERT IGNORE INTO Users (id, name, registration_date, last_visited, status, discr)
select uid,name, from_unixtime(created), from_unixtime(access),
CASE
  WHEN  status = 1 THEN 0
  WHEN  status = 0 THEN 1
  ELSE 0
END
,
 'local'
from monitor_users
;

SET SQL_MODE = '';
INSERT IGNORE INTO UsersLocal (id, username, email,password, profile_picture, cookies_accepted)
select u.id, mu.name, mu.mail, 'nopassword' , (
    select mfm.filename
    from monitor_file_managed mfm
    where  mfm.uid = mu.uid
    limit 1
  ),0
from monitor_users mu
join Users u 
on mu.uid = u.id
;
/* 

Uvoz liste zelja 
Ignore se koristi da bi preskocio proizvode koje nemamo
*/
SET SQL_MODE = '';
INSERT IGNORE into WishList (product_id, user_id)
SELECT p.id as product_id, ul.id as user_id
FROM monitor_wishlist_products mwp
join monitor_wishlists mw 
on mwp.wid = mw.wid
join monitor_users mu
on mw.uid = mu.uid
join Users ul
on mu.uid = ul.id
join monitor_artikli ma
on mwp.nid = ma.nid
join Products p 
on p.artid = ma.artid ;

/* Unos ordera 
* Dolazi do greske kada je user_id = 0 ima ih 3 ukupno to treba promeniti
*/
SET SQL_MODE = '';
INSERT IGNORE INTO Orders (id, user_id, payment_method_id, delivery_address_id , date_order, date_delivery, note, total_price, shipping_fee)
SELECT moo.order_id, (
	SELECT u.id
    FROM monitor_orders mo 
    JOIN monitor_users mu 
    on mo.uid = mu.uid
    JOIN Users u 
    on mu.uid = u.id
    where mo.order_id = moo.order_id and mu.uid > 0
    GROUP BY u.id
), 1, 1, from_unixtime(created),NULL, NULL, order_total, 0
FROM monitor_orders moo;


/* Unos proizvoda za ordere 
*  Tamo gde je null unese 0 to obrisati
*/

SET SQL_MODE = '';
insert IGNORE into OrderProducts (order_id, product_id, quantity)
SELECT mopo.order_id, (
    select p.id
    from monitor_order_products mop
    join monitor_artikli ma 
    on mop.nid = ma.nid
    join Products p 
    on ma.artid = p.artid
    where mop.order_product_id = mopo.order_product_id and mop.nid > 199420 
) as product_id, mopo.qty
FROM monitor_order_products mopo;


/* UNOS GLASANJA */

SET SQL_MODE = '';
insert IGNORE into UserProductVote (product_id, user_id, ip_address, vote)
SELECT (
	SELECT p.id
    FROM monitor_votingapi_vote mvvs
    JOIN monitor_artikli ma
    on mvvs.entity_id = ma.nid
    join Products p 
    on ma.artid = p.artid
    where mvvs.vote_id = mvv.vote_id
)as product_id, (
	SELECT ul.id
    FROM monitor_votingapi_vote mvvu
    join monitor_users mu 
    on mvvu.uid = mu.uid
    join Users ul
    on mu.uid = ul.id
    where mvvu.vote_id = mvv.vote_id
) AS user_id, mvv.vote_source, mvv.value/20 as value
FROM monitor_votingapi_vote mvv;  

/* Komentari admin */
SET SQL_MODE = '';
insert IGNORE into OrderUpdates (order_id, admin_id, comment_admin, date, status_code)
SELECT moaco.order_id, 1 as admin_id, moaco.message,from_unixtime(created), (
	select 
    IF(mo.order_status = 'canceled', 7,
      IF(mo.order_status = 'abandoned', 7,
        IF(mo.order_status = 'completed', 6,
          IF(mo.order_status = 'in_checkout', 0, 
              IF(mo.order_status = 'payment_received', 3, 
                IF(mo.order_status = 'pending', 0, 
                   IF(mo.order_status = 'processing', 0, 0) 
          		)
          	)        
          )
        )
      )
    )
    FROM monitor_order_admin_comments moac
    join monitor_orders mo
    on mo.order_id = moac.order_id
    where moac.comment_id = moaco.comment_id
) as status
FROM monitor_order_admin_comments moaco;


/* Komentari korisnik order */
SET SQL_MODE = '';
insert IGNORE into OrderUpdates (order_id, comment_user,user_notified, date, status_code)
SELECT moco.order_id, moco.message,moco.notified, from_unixtime(moco.created), (
	select 
    IF(mo.order_status = 'canceled', 7,
      IF(mo.order_status = 'abandoned', 7,
        IF(mo.order_status = 'completed', 6,
          IF(mo.order_status = 'in_checkout', 0, 
              IF(mo.order_status = 'payment_received', 3, 
                IF(mo.order_status = 'pending', 0, 
                   IF(mo.order_status = 'processing', 0, 0) 
          		)
          	)        
          )
        )
      )
    )
    FROM monitor_order_comments moc
    join monitor_orders mo
    on mo.order_id = moc.order_id
    where moc.comment_id = moco.comment_id
) as status
FROM monitor_order_comments moco;


/* Komentari korisnik */

SET SQL_MODE = '';
insert IGNORE into Comments__Main (id, parent_id, user_id, text, approved, date, discr)
SELECT mc.cid, IF(mc.pid = 0, NULL, mc.pid),  (
	SELECT ul.id
    FROM monitor_comment mcs
    join monitor_users mu 
    on mcs.uid = mu.uid
    join Users ul
    on mu.uid = ul.id
    where mcs.cid = mc.cid
) AS user_id, mc.subject, mc.status, from_unixtime(mc.created), 'product' FROM monitor_comment mc;


/* Komentari korisnik vezujem za product tabelu */
SET SQL_MODE = '';
insert IGNORE into Comments__Product (id, product_id)
SELECT mc.cid, (
    select p.id
    from monitor_comment mcc
    join monitor_artikli ma
    on mcc.nid = ma.nid
    join Products p
    on ma.artid = p.artid
    where mc.cid  = mcc.cid
) as product_id
FROM monitor_comment mc;

