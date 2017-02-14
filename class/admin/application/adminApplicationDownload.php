<?php

/**
 * Download
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class adminApplicationDownload extends adminCommon
{
     /**
     * initial execute for /reception/delete
     */
    public function run($aArgs)
    {
        $this->execute($aArgs, 'application');
        $aApplication = $this->oBlApplication->downloadApplication($this->aArgs);
        $this->writeJS('location.href="[link=admin/application/index"];');
    }
}
