<?php


class modelCertificate extends modelCommon
{
    /**
     * Get certificate
     * @return array
     */
    public function getCertificate()
    {
        $this->getDBConnection();
        $oStatement = libQueryBuilder::instance()->select()->from(libDatabaseConfig::CERTFICATE_TABLE);
        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute($aSqlParams);
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_GROUP);
        $iTotalCount = $this->getTotalCount();
        $this->closeConnection();

        return array(
            'result'      => $aResult,
            'total_count' => $iTotalCount
        );
    }

    public function saveCertificate($aParams)
    {
        $this->getDBConnection();
        $oStatement = libQueryBuilder::instance()->insert($aParams, libDatabaseConfig::CERTFICATE_TABLE);
        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $aParameters = array_merge($aParams['data'], $aParams['encrypt'] ?: array());
        $aBindParam = array();

        foreach ($aParameters as $mKey => $aValue) {
            $aBindParam[':' . $mKey] = $aValue;
        }

        $oQuery->execute($aBindParam);
        $iNewId = $this->oDBInstance->lastInsertId();
        $this->closeConnection();

        return $iNewId;
    }
}
