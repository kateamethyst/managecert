<?php

/**
 * Details
 * @package  admin
 * @author  Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class adminApplicationDetails extends adminCommon
{

    /**
     * initial run for /application/index
     */
    public function run($aArgs)
    {
        $this->execute($aArgs, 'application');
        $aCertificate = $this->oBlCertificate->getCertificate();
        $this->aArgs['certificate_id'] = 1;
        $aReceptions = $this->oBlReception->getInProgressReception($this->aArgs);
        $aAssign = array();
        $aAssign['aCertificates'] = $aCertificate['result'];
        $aAssign['aReceptions'] = $aReceptions['result'];
        $sView = 'create';

        if (libValid::isString($this->aArgs['seq_no']) === true) {
            $aApplication = $this->oBlApplication->findApplication($this->aArgs['seq_no']);
            if (libValid::isArray($aApplication['result']) === false) {
                $this->writeJS('location.href="[link=admin/application/details"];');
                return true;
            } else {
                if ($aApplication['result'][0]['supplier_id'] !== $this->aArgs['user_id'] && $this->aArgs['mall_version'] === libConfig::SUPPLIER) {
                    $this->writeJS('location.href="[link=admin/application/details"];');
                    return true;
                }
            }
            $bEditable = $this->oBlApplication->checkIfEditable($aApplication['result'][0], $this->aArgs['mall_version']);
            $aAssign['aApplication'] = $aApplication['result'][0];
            $aAssign['aConditions'] = $this->aArgs['application_condition'];
            $aAssign['bEditable'] = $bEditable;

            $sView = 'edit';
        }
        $aAssign['aArgs'] = $this->aArgs;
        $this->arrayAssign($aAssign);
        $this->setView('application/' . $sView);

    }
}
