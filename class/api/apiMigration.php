<?php

/**
 * Application
 * @package  api
 * @author  Perseus
 * @version  1.0
 * @since  since 2017.01.30
 */
class apiMigration extends Controller_Api
{
    protected function post($aArgs)
    {

        $oMigration = new blMigration($this->Upload, $this->Storage);
        $aArgs['sAppId'] = $this->Request->getAppID();
        $aArgs['sDomain'] = $this->Request->getDomain();
        $bResult = $oMigration->migrateFile($aArgs);


        
        return $bResult;
    }

}
