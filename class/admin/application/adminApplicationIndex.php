<?php

/**
 * Index
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class adminApplicationIndex extends adminCommon
{
    /**
     * initial run for /reception/index
     */
    public function run($aArgs)
    {
        $this->execute($aArgs, 'application');
        $aCertificate = $this->oBlCertificate->getCertificate();
        $aReception = $this->oBlReception->getReception($this->aArgs);
        $aApplication = $this->oBlApplication->getPaginatedApplication($this->aArgs);
        $aSuppliers = $this->oBlApplication->getSuppliers($this->aArgs);
        $this->aArgs['total_count'] = $aApplication['total_count'];

        $sPage = $this->setDefaultValue('application');
        if ($this->aArgs['page'] < 1) {
            $this->writeJS('location.href=[link=admin/application/index]');
            return;
        }

        $aPagingParam = $this->setPagingParam('[link=admin/application/index]');

        $aPagingData = libPaging::getInstance()->getPaging($aPagingParam);

        $aAssign = array(
            'aCertificates' => $aCertificate['result'],
            'aConditions'   => libConfig::applicationStatus(),
            'aSuppliers'    => $aSuppliers['result'],
            'aReceptions'   => $aReception['result'],
            'aApplications' => $aApplication['result'],
            'aPaging'       => $aPagingData,
            'aPage'         => $aPagingParam,
            'aAcceptance'   => $this->aArgs['remarks'],
            'aArgs'         => $this->aArgs
        );
        $this->arrayAssign($aAssign);
        $this->setView('application/index');
    }
}
