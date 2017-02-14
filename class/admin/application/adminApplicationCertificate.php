<?php

/**
 * Certificate
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class adminApplicationCertificate extends adminCommon
{
    /**
     * initial execute for /reception/certificate
     */
    public function run($aArgs)
    {
        $this->execute($aArgs, 'certificate');
        $sCertificate = $this->oBlApplication->getCertificate($this->aArgs['seq_no']);
        $aAssign = array(
            'sCertificate' => $sCertificate
        );
        $this->arrayAssign($aAssign);
        $this->setView('application/certificate');
    }
}
