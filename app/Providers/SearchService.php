<?php

namespace App\Providers;

class SearchService extends BaseService {

    /**
     * Pretražuje proizvode po nazivu
     * @param   string          $search             Parametar po kom pretražuje
     * @return  array           Vraća niz objekata
     */
    public static function searchProductsByName($name, $limit = 10, $last = null, $direction = false) {
        $direction              = $direction === true ? 'ASC' : 'DESC';
        $qb = self::$entity_manager->createQueryBuilder();
        $products = $qb
            ->select('p')
            ->from('App\Models\Product', 'p')
            ->where('p.name LIKE :name')
            ->andWhere('p.published = 1')
            ->andWhere('p.price_discount > 0 OR p.presales = 1')
            ->andWhere('p.price_retail > 0 OR p.presales = 1')
            ->orderBy('p.artid', $direction)
            ->setParameter('name', '%' . $name . '%')
            ->setMaxResults($limit)
        ;

        if ($last !== null && $last !== '') {
            $dir = $direction === 'ASC' ? '>=' : '<=';
            $products
                ->andWhere('p.artid' . $dir . ':last')
                ->setParameter('last', $last)
            ;
        }

        return $products
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Ptretražuje proizvode po artid-u ili imenu
     * @param   int/string      $search             Parametar po kom se ptražuje
     * @param   array           $ids                Id-jevi proizvoda koji se iskljucuju iz pretrage
     * @return  array           Vraća niz objekata
     */
    public static function searchProductsByArtidOrName($search, $ids = [], $stock = null) {
        $qb = self::$entity_manager->createQueryBuilder();

        $search = $qb
            ->select('p')
            ->from('App\Models\Product', 'p')
            ->where('p.artid LIKE :artid')
            ->setParameter('artid', '%' . $search . '%')
            ->orWhere('p.name LIKE :name')
            ->setParameter('name', '%' . $search . '%')
        ;
        if (!empty($ids)) {
            $search->andWhere($qb->expr()->notIn('p.id', $ids));
        }

        if ($stock !== null) {
            $subquery = self::$entity_manager->createQueryBuilder();
            $subquery
                ->select('sp')
                ->from('App\Models\StockShop', 'sp')
                ->where('sp.product_id = p.id')
            ;


            $stockOr = $qb->expr()->orX();
            $stockOr->add($qb->expr()->gt('p.stock_warehouse', 0));
            $stockOr->add($qb->expr()->exists($subquery->getDql()));

            $search
                ->andWhere($stockOr)
            ;
        }

        return $search
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
