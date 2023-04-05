<?php

namespace App\Providers;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

class DeviceService extends BaseService {
    public static function getDeviceInfo() {
        DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
        $userAgent = $_SERVER['HTTP_USER_AGENT']; // change this to the useragent you want to parse
        $dd = new DeviceDetector($userAgent);
        $dd->parse();

        if ($dd->isBot()) {
            // handle bots,spiders,crawlers,...
            $botInfo = $dd->getBot();
            return 'bot';
        } else {
            $clientInfo = $dd->getClient(); // holds information about browser, feed reader, media player, ...
            $osInfo = $dd->getOs();
            $device = $dd->getDeviceName();
            $brand = $dd->getBrandName();
            $model = $dd->getModel();
            return $osInfo['name'] . ' ' . $model;
        }
    }
}
