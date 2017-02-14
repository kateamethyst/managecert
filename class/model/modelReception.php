<?php

class modelReception extends modelCommon
{
    /**
     * [getReception description]
     * @return [type] [description]
     */
    public function getReception($aParams)
    {
        $this->getDBConnection();

        $aSqlParams = array(
            'is_deleted' => 'F'
        );

        $oStatement = libQueryBuilder::instance()
            ->select(
                libDatabaseConfig::RECEPTION_TABLE . '.*,'
                . libDatabaseConfig::DEPARTMENT_TABLE . '.seq_no as dep_id,'
                . libDatabaseConfig::DEPARTMENT_TABLE . '.department_name,'
                . libDatabaseConfig::DEPARTMENT_TABLE . '.department_code'
            )
            ->from(libDatabaseConfig::RECEPTION_TABLE)
            ->innerJoin(libDatabaseConfig::DEPARTMENT_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_reception_department_seq_no', libDatabaseConfig::DEPARTMENT_TABLE . '.seq_no');

        if (isset($aParams['ixnn_certificate_seq_no']) === true) {
            $oStatement->innerJoin(libDatabaseConfig::CERTFICATE_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', libDatabaseConfig::CERTFICATE_TABLE . '.seq_no');
            $aSqlParams['ixnn_certificate_seq_no'] = $aParams['ixnn_certificate_seq_no'];
        }

        $oStatement->where(libDatabaseConfig::RECEPTION_TABLE . '.is_deleted', ':is_deleted');

        if (isset($aParams['ixnn_certificate_seq_no']) === true) {
            $oStatement->andWhere(libDatabaseConfig::RECEPTION_TABLE. '.ixnn_certificate_seq_no', ':ixnn_certificate_seq_no');
        }

        if (isset($aParams['supplier_id']) === true) {
            $oStatement->andWhere(libDatabaseConfig::RECEPTION_TABLE . '.supplier_id', ':supplier_id');
            $aSqlParams['supplier_id'] = $aParams['supplier_id'];
        }

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute($aSqlParams);
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);
        $iTotalCount = $this->getTotalCount();
        $this->closeConnection();

        return array(
            'result'      => $aResult,
            'total_count' => $iTotalCount
        );

    }

    /**
     * get all In progress receptions. Basically for admin
     * @return [type] [description]
     */
    public function getAllInProgress($aParams)
    {
        $this->getDBConnection();

        $aSqlParams = array(
            'is_deleted'       => 'F',
            'condition_status' => $aParams['condition_status']
        );

        $oStatement = libQueryBuilder::instance()
            ->select(
                libDatabaseConfig::RECEPTION_TABLE . '.*,'
                . libDatabaseConfig::DEPARTMENT_TABLE . '.seq_no as dep_id,'
                . libDatabaseConfig::DEPARTMENT_TABLE . '.department_name,'
                . libDatabaseConfig::DEPARTMENT_TABLE . '.department_code'
            )
            ->from(libDatabaseConfig::RECEPTION_TABLE)
            ->innerJoin(libDatabaseConfig::DEPARTMENT_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_reception_department_seq_no', libDatabaseConfig::DEPARTMENT_TABLE . '.seq_no');

        if (isset($aParams['ixnn_certificate_seq_no']) === true) {
            $oStatement->innerJoin(libDatabaseConfig::CERTFICATE_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', libDatabaseConfig::CERTFICATE_TABLE . '.seq_no');
            $aSqlParams['ixnn_certificate_seq_no'] = $aParams['ixnn_certificate_seq_no'];
        }

        $oStatement->where(libDatabaseConfig::RECEPTION_TABLE . '.is_deleted', ':is_deleted')
        ->andWhere(libDatabaseConfig::RECEPTION_TABLE. '.condition_status', ':condition_status');
        if (isset($aParams['ixnn_certificate_seq_no']) === true) {
            $oStatement->andWhere(libDatabaseConfig::RECEPTION_TABLE. '.ixnn_certificate_seq_no', ':ixnn_certificate_seq_no');
        }

        if (isset($aParams['supplier_id']) === true) {
            $oStatement->andWhere(libDatabaseConfig::RECEPTION_TABLE . '.supplier_id', ':supplier_id');
            $aSqlParams['supplier_id'] = $aParams['supplier_id'];
        }

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute($aSqlParams);
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);
        $iTotalCount = $this->getTotalCount();
        $this->closeConnection();

        return array(
            'result'      => $aResult,
            'total_count' => $iTotalCount
        );
    }

    /**
     * reception search for admin
     * @param  [type] $aParams [description]
     * @return [type]          [description]
     */
    public function getAdminFilteredReception($aParams)
    {
        $this->getDBConnection();
        $aSqlParams = array(
            'is_deleted' => 'F'
        );
        $oStatement = libQueryBuilder::instance()
            ->select(
                'SQL_CALC_FOUND_ROWS '
                . libDatabaseConfig::RECEPTION_TABLE . '.*, '
                . libDatabaseConfig::CERTFICATE_TABLE . '.name as certificate_name, '
                . libDatabaseConfig::CERTFICATE_TABLE . '.is_active as certificate_active, '
                . libDatabaseConfig::DEPARTMENT_TABLE . '.department_name'
            )
            ->from(libDatabaseConfig::RECEPTION_TABLE)
            ->innerJoin(libDatabaseConfig::CERTFICATE_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', libDatabaseConfig::CERTFICATE_TABLE . '.seq_no')
            ->innerJoin(libDatabaseConfig::DEPARTMENT_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_reception_department_seq_no', libDatabaseConfig::DEPARTMENT_TABLE . '.seq_no')
            ->where(libDatabaseConfig::RECEPTION_TABLE . '.is_deleted', ':is_deleted');

        if (libValid::isString($aParams['classification']) === true && libValid::isString($aParams['classification_value']) === true) {
            $oStatement->andWhereLike($aParams['classification'], $aParams['classification_value']);
        }

        if (isset($aParams['supplier_id']) === true) {
            $oStatement->andWhere(libDatabaseConfig::RECEPTION_TABLE . '.supplier_id', ':supplier_id');
            $aSqlParams['supplier_id'] = $aParams['supplier_id'];
        }

        //Condition
        if (isset($aParams['condition_status']) === true && libValid::isArray($aParams['condition_status']) === true) {
            $oStatement->andWhereIn(libDatabaseConfig::RECEPTION_TABLE . '.condition_status', $aParams['condition_status']);
        }

        //Department
        if (isset($aParams['ixnn_reception_department_seq_no']) === true && libValid::isArray($aParams['ixnn_reception_department_seq_no']) === true) {
            $oStatement->andWhereIn(libDatabaseConfig::RECEPTION_TABLE . '.ixnn_reception_department_seq_no', $aParams['ixnn_reception_department_seq_no']);
        }

        //Date
        if (isset($aParams['start_date']) === true) {
            $oStatement->andBetween(libDatabaseConfig::RECEPTION_TABLE . '.upd_timestamp', array('start_date' => ':start_date', 'end_date' => ':end_date'));
            $aSqlParams['end_date'] = $aParams['end_date'];
            $aSqlParams['start_date'] = $aParams['start_date'];
        }

        $oStatement->andWhere(libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', libDatabaseConfig::CERTFICATE_TABLE . '.seq_no')->andWhere(libDatabaseConfig::RECEPTION_TABLE . '.ixnn_reception_department_seq_no', libDatabaseConfig::DEPARTMENT_TABLE . '.seq_no');

        //Orderby
        $oStatement->orderBy(libDatabaseConfig::RECEPTION_TABLE . '.upd_timestamp', 'D');

        if (isset($aParams['iLimit']) === true) {
            $oStatement->limit($aParams['iStart'] ?: 0, $aParams['iLimit']);
        }

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute($aSqlParams);
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);
        $iTotalCount = $this->getTotalCount();
        $this->closeConnection();
        return array(
            'result'      => $aResult,
            'total_count' => $iTotalCount
        );
    }

    /**
     * reception search by supplier id
     * @param  [type] $iSupplierId [description]
     * @param  [type] $aParams     [description]
     * @return [type]              [description]
     */
    public function getFilteredReceptionBySupplier($iSupplierId, $aParams)
    {
        $this->getDBConnection();
        $aSqlParams = array(
            'is_deleted'  => 'F',
            'supplier_id' => $iSupplierId
        );
        $oStatement = libQueryBuilder::instance()
            ->select(
                'SQL_CALC_FOUND_ROWS '
                . libDatabaseConfig::RECEPTION_TABLE . '.*, '
                . libDatabaseConfig::CERTFICATE_TABLE . '.name as certificate_name, '
                . libDatabaseConfig::CERTFICATE_TABLE . '.is_active as certificate_active, '
                . libDatabaseConfig::DEPARTMENT_TABLE . '.department_name'
            )
            ->from(libDatabaseConfig::RECEPTION_TABLE)
            ->innerJoin(libDatabaseConfig::CERTFICATE_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', libDatabaseConfig::CERTFICATE_TABLE . '.seq_no')
            ->innerJoin(libDatabaseConfig::DEPARTMENT_TABLE, libDatabaseConfig::RECEPTION_TABLE . '.ixnn_reception_department_seq_no', libDatabaseConfig::DEPARTMENT_TABLE . '.seq_no')
            ->where(libDatabaseConfig::RECEPTION_TABLE . '.is_deleted', ':is_deleted')
            ->andWhere(libDatabaseConfig::RECEPTION_TABLE . '.supplier_id', ':supplier_id');

        if (libValid::isString($aParams['classification']) === true) {
            $oStatement->andWhereLike($aParams['classification'], $aParams['classification_value']);
        }

        //Condition
        if (isset($aParams['condition_status']) === true && libValid::isArray($aParams['condition_status']) === true) {
           $oStatement->andWhereIn(libDatabaseConfig::RECEPTION_TABLE . '.condition_status', $aParams['condition_status']);
        }

        //Department
        if (isset($aParams['ixnn_reception_department_seq_no']) === true && libValid::isArray($aParams['ixnn_reception_department_seq_no']) === true) {
            $oStatement->andWhereIn(libDatabaseConfig::RECEPTION_TABLE . '.ixnn_reception_department_seq_no', $aParams['ixnn_reception_department_seq_no']);
        }

        //Date
        if (isset($aParams['start_date']) === true) {
            $oStatement->andBetween(libDatabaseConfig::RECEPTION_TABLE . '.upd_timestamp', array('start_date' => ':start_date', 'end_date' => ':end_date'));
            $aSqlParams['end_date'] = $aParams['end_date'];
            $aSqlParams['start_date'] = $aParams['start_date'];
        }

        $oStatement->andWhere(libDatabaseConfig::RECEPTION_TABLE . '.ixnn_certificate_seq_no', libDatabaseConfig::CERTFICATE_TABLE . '.seq_no')->andWhere(libDatabaseConfig::RECEPTION_TABLE . '.ixnn_reception_department_seq_no', libDatabaseConfig::DEPARTMENT_TABLE . '.seq_no');
        //Orderby
        $oStatement->orderBy(libDatabaseConfig::RECEPTION_TABLE . '.upd_timestamp', 'D');

        if (isset($aParams['iLimit']) === true) {
            $oStatement->limit($aParams['iStart'] ?: 0, $aParams['iLimit']);
        }

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute($aSqlParams);
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);
        $iTotalCount = $this->getTotalCount();

        $this->closeConnection();

        return array(
            'result'      => $aResult,
            'total_count' => $iTotalCount
        );
    }

    /**
     * search reception by $aParams passed
     * @param  [type] $aParams [description]
     * @return [type]          [description]
     */
    public function searchReception($aParams)
    {
        $this->getDBConnection();
        $oStatement = libQueryBuilder::instance()
            ->select()
            ->from(libDatabaseConfig::RECEPTION_TABLE)
            ->where(key($aParams))
            ->andWhere('is_deleted');
        $aSqlParams = array(
            key($aParams) => $aParams[key($aParams)],
            'is_deleted'  => 'F'
        );

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute($aSqlParams);
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);
        $iTotalCount = $this->getTotalCount();

        $this->closeConnection();

        return array(
            'result'      => $aResult,
            'total_count' => $iTotalCount
        );
    }

    /**
     * update recetion(s) by id
     * @param  [type] $aIds    [description]
     * @param  [type] $aParams [description]
     * @return [type]          [description]
     */
    public function updateReceptionById($aIds, $aParams)
    {
        $this->getDBConnection();
        $aSqlParams = array(
            'data' => $aParams
        );

        $oStatement = libQueryBuilder::instance()->update($aSqlParams, libDatabaseConfig::RECEPTION_TABLE)->whereIn('seq_no', $aIds);
        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $mResult = $oQuery->execute($aSqlParams['data']);
        $this->closeConnection();

        return $mResult;
    }

    /**
     * insert Reception
     * @param  [type] $aParams [description]
     * @return [type]          [description]
     */
    public function insertReception($aParams)
    {
        $this->getDBConnection();
        $aSqlParams = array(
            'data' => $aParams
        );

        $oStatement = libQueryBuilder::instance()->insert($aSqlParams, libDatabaseConfig::RECEPTION_TABLE);
        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);

        $oQuery->execute($aSqlParams['data']);
        $iNewId = $this->oDBInstance->lastInsertId();
        $this->closeConnection();

        return $iNewId;
    }

    public function getAllReception()
    {
        $this->getDBConnection();
        $oStatement = libQueryBuilder::instance()
            ->select()
            ->from(libDatabaseConfig::RECEPTION_TABLE);

        $oQuery = $this->oDBInstance->prepare($oStatement->sStatement);
        $oQuery->execute();
        $aResult = $oQuery->fetchAll(PDO::FETCH_ASSOC);
        $iTotalCount = $this->getTotalCount();

        $this->closeConnection();

        return array(
            'result' => $aResult,
            'total_count' => $iTotalCount
        );
    }
}
