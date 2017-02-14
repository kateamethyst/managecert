<?php

/**
 * Business logic for supplier
 * @package  bl
 * @author   Ma. Angelica Concepcion <concepcionmaangelica@gmail.com>
 * @version  1.0
 * @since  since 2016.12.19
 */
class blSupplier
{
    /**
     * instance of modelManageCert
     * @var object
     */
    private $oModel;

    public function __construct($oModel)
    {
        $this->oModel = $oModel;
    }

    /**
     * Get supplier/s
     * @param  array $aParams  Supplier information
     * @return array
     */
    public function getSupplier()
    {
        // if ($aParams['mall_version'] === 'A' || $aParams['mall_version'] === 'P') {
        //Statement
            $oStatement = libQueryBuilder::instance()->select()->from(libDatabaseConfig::SUPPLIER_TABLE);
            $aSqlParams = array(
                'query' => $oStatement->sStatement
            );
            return $this->oModel->get($aSqlParams);
        // }
        //  return $this->findSupplier($aParams);

    }

    /**
     * Find supplier
     * @param  array $aParams paramters
     * @return array
     */
    public function findSupplier($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return false;
        }

        //Statement
        $oStatement = libQueryBuilder::instance()->select()->from(libDatabaseConfig::SUPPLIER_TABLE)->where('supplier_id');

        //Parameters
        $aSqlParams = array(
            'query'  => $oStatement->sStatement,
            'params' => array(
                'supplier_id' => $aParams['ixnn_supplier_seq_no']
            )
        );

        $aSupplier = $this->oModel->get($aSqlParams);

        if (libValid::isArray($aSupplier) === false) {
            return $this->saveSupplier($aSqlParams['params']);
        }

        return $aSupplier;

    }

    /**
     * Save supplier
     * @param array $aParams Parameters
     * @return array
     */
    public function saveSupplier($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return false;
        }
        $aSqlParams = array(
            'data' => $aParams
        );
        //Statement
        $oStatement = libQueryBuilder::instance()->insert($aSqlParams, libDatabaseConfig::SUPPLIER_TABLE);

        //Parameters
        $aSqlParams = array(
            'query'  => $oStatement->sStatement,
            'params' => $aParams
        );

        return $this->oModel->insert($aSqlParams, libDatabaseConfig::SUPPLIER_TABLE);
    }
}
