<?php

require_once dirname(__FILE__) . '/PHPExcel.php';


class libExcel
{
    public static $oInstance;
    public static function getInstance()
    {
        if(isset(self::$oInstance) === false) {
            self::$oInstance = new libExcel();
        }

        return self::$oInstance;
    }
    public function getExcel($path)
    {
        $oPhpExcel = new PHPExcel();
        $oOutput = PHPExcel_IOFactory::load($path);
        return $oOutput->getActiveSheet()->toArray();
    }
}
