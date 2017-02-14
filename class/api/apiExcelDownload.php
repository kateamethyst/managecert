<?php

/**
 * Download
 * @package  api
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class apiExcelDownload extends Controller_Api
{
    /**
     * Get
     * @param  array    $aArgs List of parameters
     * @return mixed
     */
    protected function get($aArgs)
    {
        // $bLogin = $this->Service->isLogin();
        // if ($bLogin === true) {
            $oBlApplication = libBlInstance::getBlApplication();
            $aArgs['mall_id'] = $this->Request->getDomain();
            $aApplication = $oBlApplication->downloadApplication($aArgs, $this->Openapi);
            return $aApplication;
        // }
        // return false;
    }
}
