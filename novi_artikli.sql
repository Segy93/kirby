
use monitornew;

select concat('/opt/remi/php73/root/usr/bin/php artisan import:data artid ', ma.artid)
from monitor_artikli ma  
inner join monitor_kategorije mk on ma.tipid = mk.tipid  
inner join  Categories c on mk.tip = c.name_import  
left join Products pr on ma.artid = pr.artid where pr.artid is null 
order by ma.artid desc;
