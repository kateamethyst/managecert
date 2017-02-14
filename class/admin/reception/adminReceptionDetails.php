<?php

/**
 * Create and update
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class adminReceptionDetails extends adminCommon
{
    /**
     * initial run for /reception/index
     */
    public function run($aArgs)
    {
        $this->execute($aArgs, 'reception');
        $aDepartment = $this->oBlDepartment->getDepartment();
        $aCertificate = $this->oBlCertificate->getCertificate();

        $aAssign = array();

        $aAssign['aCertificates'] = $aCertificate['result'];
        $aAssign['aDepartments'] = $aDepartment['result'];
        $sView = 'create';

        if (libValid::isString($this->aArgs['seq_no']) === true) {
            $aReception = $this->oBlReception->findReception(array('seq_no' => $this->aArgs['seq_no']));
            if (libValid::isArray($aReception['result']) === false) {
                $this->writeJS('location.href="[link=admin/reception/details"];');
                return true;
            } else {
                if ($aReception['result'][0]['supplier_id'] !== $this->aArgs['user_id'] && $this->aArgs['mall_version'] === libConfig::SUPPLIER) {
                    $this->writeJS('location.href="[link=admin/reception/details"];');
                    return true;
                }
            }
            $aAssign['aReception'] = $aReception['result'][0];
            $sView = 'edit';
        }
        $this->arrayAssign($aAssign);
        $this->setView('reception/' . $sView);
    }
}
