<?php

/**
 * Reception
 * @package  api
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class apiReception extends Controller_Api
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
            $aArgs['user_id'] = $this->Service->getUserID();
            $oBlReception = libBlInstance::getBlReception();
            return $oBlReception->setMethod($aArgs, 'get');
        // }
        // return false;
    }
}
