use monitornew;

truncate monitor_update_sajt;

insert into monitor_update_sajt(artid, kljuc, vrednost, f_obrisano, id)
select *, row_number() over ()
from (
select pr.artid, 'UCITAJSLIKU', '', 0
from ProductPictures pp
inner join Products pr on pp.product_id = pr.id
left join opis_slika os on os.artid = pr.artid and os.rb = pp.position and pp.name = os.fajl
where os.artid is null
union 
select os.artid, 'UCITAJSLIKU', '', 0
from ProductPictures pp
inner join Products pr on pp.product_id = pr.id
right join opis_slika os on os.artid = pr.artid and os.rb = pp.position and pp.name = os.fajl
where pr.artid is null)x;


select distinct concat('/opt/remi/php73/root/usr/bin/php artisan update:data artid ', artid)
from monitor_update_sajt;
