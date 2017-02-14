<?php

/**
 * Database connection and execution of query
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class adminReceptionIndex extends adminCommon
{
    /**
     * initial run for /reception/index
     */
    public function run($aArgs)
    {
        $this->execute($aArgs, 'reception');
        $aReception = $this->oBlReception->getPaginatedReception($this->aArgs);
        $this->aArgs['total_count'] = $aReception['total_count'];
        $aDepartment = $this->oBlDepartment->getDepartment();
        $aCertificate = $this->oBlCertificate->getCertificate();

        $sPage = $this->setDefaultValue('reception');

        $aPagingParam = $this->setPagingParam('[link=admin/reception/index]');
        $aPagingData = libPaging::getInstance()->getPaging($aPagingParam);

        $this->aArgs['reception_id'] = $aReception['result'];
        $aAssign = array(
            'aReceptions'   => $aReception['result'],
            'aDepartments'  => $aDepartment['result'],
            'aCertificates' => $aCertificate['result'],
            'aPaging'       => $aPagingData,
            'aPage'         => $aPagingParam,
            'aCondition'    => libConfig::receptionStatus(),
            'aArgs'         => $this->aArgs
        );
        $this->arrayAssign($aAssign);
        $this->setView('reception/index');
    }
}
