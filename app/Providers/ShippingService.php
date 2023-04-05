<?php

namespace App\Providers;

use App\Providers\ShopService;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorJPG;
use Picqer\Barcode\BarcodeGeneratorHTML;

class ShippingService {

    private static $shipment_type = 2;

    //Pefiks bar koda
    private static $barcodePrefix = 'MS';

    //Limit barkoda
    private static $barcodeLimit = 100000;

    //Dužina barkoda
    private static $barcodeLength = 10;

    public static function generateBarcode($order_id) {
        $barcode        =   $order_id % self::$barcodeLimit;
        $zeroescunt     =   self::$barcodeLength - ceil(log10($barcode));
        return self::$barcodePrefix . str_repeat('0', $zeroescunt) . $barcode;
    }

    public static function generateBarcodeSVG() {
        $generator = new BarcodeGeneratorSVG();

        return $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
    }

    public static function generateBarcodePNG() {
        $generator = new BarcodeGeneratorPNG();
        return '<img src="data:image;base64,'
            . base64_encode($generator->getBarcode('081231723897', $generator::TYPE_CODE_128))
            . '">'
        ;
    }

    public static function generateBarcodeJPG() {
        $generator = new BarcodeGeneratorJPG();
        return '<img src="data:image;base64,'
            . base64_encode($generator->getBarcode('081231723897', $generator::TYPE_CODE_128))
            . '">'
        ;
    }

    public static function generateBarcodeHTML() {
        $generator = new BarcodeGeneratorHTML();
        return $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
    }

    /**
     * Generiše csv file za narudžbinu
     * @param   Order       $order      Prima order objekat
     * @return  void
     */
    public static function generateCSV($order) {
        ob_start();

        $fp = fopen('../OrderCsvFiles/order_' . $order->id . '.csv', 'w');
        $headers = [
            'SBranchID',
            'SName',
            'SAddress',
            'STownID',
            'STown',
            'SCName',
            'SCPhone',
            'PuBranchID',
            'PuName',
            'PuAddress',
            'PuTownID',
            'PuTown',
            'PuCName',
            'PuCPhone',
            'RBranchID',
            'RName',
            'RAddress',
            'RTownID',
            'RTown',
            'RCName',
            'RCPhone',
            'DlTypeID',
            'PaymentBy',
            'PaymentType',
            'BuyOut',
            'BuyOutFor',
            'BuyOutAccount',
            'Value',
            'Mass',
            'ReturnDoc',
            'SMS_Sender',
            'Packages',
            'Note',
            'ReferenceID',
            'Content'
        ];
        fputcsv($fp, $headers);
        fputcsv($fp, [
            'SBranchID'         =>  'nepoznato polje',
            'SName'             =>  'nepoznato polje',
            'SAddress'          =>  'nepoznato polje',
            'STownID'           =>  'nepoznato polje',
            'STown'             =>  'nepoznato polje',
            'SCName'            =>  'nepoznato polje',
            'SCPhone'           =>  'nepoznato polje',
            'PuBranchID'        =>  'nepoznato polje',
            'PuName'            =>  'nepoznato polje',
            'PuAddress'         =>  'nepoznato polje',
            'PuTownID'          =>  'nepoznato polje',
            'PuTown'            =>  'nepoznato polje',
            'PuCName'           =>  'nepoznato polje',
            'PuCPhone'          =>  'nepoznato polje',
            'RBranchID'         =>  'nepoznato polje',
            'RName'             =>  $order->delivery_address->contact_surname . ' ' . $order->delivery_address->contact_name,
            'RAddress'          =>  $order->delivery_address->address,
            'RTownID'           =>  $order->delivery_address->postal_code,
            'RTown'             =>  $order->delivery_address->city,
            'RCName'            =>  $order->delivery_address->contact_surname . ' ' . $order->delivery_address->contact_name,
            'RCPhone'           =>  $order->delivery_address->phone_nr,
            'DlTypeID'          =>  self::$shipment_type,
            'PaymentBy'         =>  3,
            'PaymentType'       =>  $order->payment_method_id,
            'BuyOut'            =>  0,
            'BuyOutFor'         =>  1,
            'BuyOutAccount'     =>  'koji je broj tekućeg računa?',
            'Value'             =>  $order->total_price,
            'Mass'              =>  ShopService::calculateShippingWeight($order->id),
            'ReturnDoc'         =>  0,
            'SMS_Sender'        =>  'Nepoznato polje',
            'Packages'          =>  self::generateBarcode($order->id),
            'Note'              =>  $order->note,
            'ReferenceID'       =>  'Nepoznato polje',
            'Content'           =>  'Kompjuterski delovi',
        ]);

        return fclose($fp);
    }
}
