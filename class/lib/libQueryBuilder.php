<?php

/**
 * Query Builder
 * @package class/lib
 * @author  Ma. Angelica B. Concepcion
 * @version 1.0
 * @since  2016.12.19
 */
class libQueryBuilder
{
    /**
     * MySQL Statement
     * @var string
     */
    public $sStatement;

    /**
     * libQueryBuilder instance
     * @var object
     */
    public static $oInstance;

    /**
     * modelDB instance
     * @return object
     */
    public static function instance()
    {
        if (self::$oInstance === null) {
            self::$oInstance = new libQueryBuilder();
        }
        return self::$oInstance;
    }
    /**
     * Handdle Params
     * @param  array $aParams List of parameters
     * @return array
     */
    private function handleParams($aParams)
    {
        if (libVAlid::isNull($aParams) === true) {
            return false;
        }
        $sFields = implode(', ', array_keys($aParams));
        $sValues = implode(', :', array_keys($aParams));

        return array(
            'fields' => $sFields,
            'values' => $sValues,
            'params' => $aParams
        );
    }

    /**
     * SELECT
     * @param  string    $aParams    List of parameters
     * @return object
     */
    public function select($aParams = '*')
    {
        $this->sStatement = 'SELECT ' . $aParams;
        return $this;
    }

    /**
     * FROM
     * @param  string    $sTableName     Table name
     * @return object
     */
    public function from($mTableName)
    {
        if (libValid::isArray($mTableName) === true) {
            $sTableName = implode(', ', $mTableName);
        } else {
            $sTableName = $mTableName;
        }

        $this->sStatement .= ' FROM ' . $sTableName;
        return $this;
    }

    /**
     * WHERE
     * @param  array   $aParams    List of parameters
     * @return object
     */
    public function where($sParam, $sValue = null)
    {
        $this->sStatement .= ' WHERE ' . $sParam . ' = ' . ($sValue ?: (':' . $sParam));
        return $this;
    }

    /**
     * ADNWHEREIN
     * @param  array   $aParams    List of parameters
     * @return object
     */
    public function andWhereIn($sColumnName, $aValue)
    {
        if (isset($aValue[0]) === true && $aValue[0] === 'on') {
            unset($aValue[0]);
        }
        $aTemp = $aValue;
        foreach ($aTemp as $iKey => $mValue) {
            $aValue[$iKey] = libValid::isNumeric($mValue) === true ? $mValue : ('"' . $mValue . '"');
        }
        $sValue = implode(',', array_values($aValue));
        $this->sStatement .= ' AND ' . $sColumnName . ' IN ' . '(' . $sValue . ') ';
        return $this;
    }

    /**
     * WHEREIN
     * @param  array   $aParams    List of parameters
     * @return object
     */
    public function whereIn($sColumnName, $aValue)
    {
        $sValue = implode(',', array_values($aValue));
        $sValue = ltrim($sValue, 'on');
        $sValue = ltrim($sValue, ',');
        $this->sStatement .= ' WHERE ' . $sColumnName . ' IN ' . '(' . $sValue . ') ';
        return $this;
    }
    /**
     * AND
     * @param  array    $aParams    List of Parameters
     * @return object
     */
    public function andWhere($sParam, $sValue = null)
    {
        $this->sStatement .= ' AND ' . $sParam . ' = ' . ($sValue ?: (':' . $sParam));
        return $this;
    }

    /**
     * OR
     * @param  array  $aParams   List of parameters
     * @return object
     */
    public function orWhere($sParam, $sValue = null)
    {
        $this->sStatement .= ' OR ' . $sParam . ' = ' . ($sValue ?: (':' . $sParam));
        return $this;
    }

    /**
     * where like
     * @param  array    $sParams   List of Parameters
     * @param  string   $sValue    Value
     * @return object
     */
    public function andWhereLike($sParams, $sValue = null)
    {
        $this->sStatement .= ' AND ' . $sParams . ' LIKE "%'  . ($sValue ?: (':' . $sParams)) . '%"';
        return $this;
    }

    /**
     * inner join
     * @param  string $sTableName Table to join
     * @param  string $sColumn1   left table column
     * @param  string $sColumn2   right table column
     * @return object
     */
    public function innerJoin($sTableName, $sColumn1, $sColumn2)
    {
        $this->sStatement .= ' INNER JOIN ' . $sTableName . ' ON ' . $sColumn1 . ' = ' . $sColumn2;
        return $this;
    }

    /**
     * OR
     * @param  array  $aParams  List of parameters
     * @return object
     */
    public function like($aParams)
    {
        $this->sStatement .= ' LIKE ' . $aParams;
        return $this;
    }

    /**
     * ORDER BY
     * @param  array    $aParams   List of parameters
     * @param  string   $sOrder    Order (A = Ascending || D = Descending)
     * @return object
     */
    public function orderBy($sParams, $sOrder)
    {
        if ($sOrder === 'A') {
            $sOrder = 'ASC';
        } else {
            $sOrder = 'DESC';
        }

        $this->sStatement .= ' ORDER BY '  . $sParams . ' ' . $sOrder;

        return $this;
    }

    /**
     * INSERT
     * @param  array    $aParams       List of parameters
     * @param  string   $sTableName    Table name
     * @return object
     */
    public function insert($aParams, $sTableName)
    {
        $aData = $this->handleParams($aParams['data']);
        $aEncrypt = $this->encryptParameters($aParams['encrypt']);
        $this->sStatement = 'INSERT INTO ' . $sTableName . '(' . $aData['fields'] . $aEncrypt['fields'] . ') VALUE(:' . $aData['values'] . $aEncrypt['values'] . ')';
        return $this;
    }

    /**
     * UPDATE
     * @param  array    $aParams       List of paramters
     * @param  string   $sTableName    Table name
     * @return object
     */
    public function update($aParams, $sTableName)
    {
        $sTobeUpdate = '';

        foreach ($aParams['data'] as $sKey => $sValue) {
            $sTobeUpdate = $sTobeUpdate . $sKey  . '=:' . $sKey . ', ';
        }

        if (libValid::isArray($aParams['encrypt']) === true) {
            foreach ($aParams['encrypt'] as $sKey => $sValue) {
                $sTobeUpdate = $sTobeUpdate . $sKey  . ' = HEX(AES_ENCRYPT(:' . $sKey . ', "' . libDatabaseConfig::KEY . '")), ';
            }
        }

        $sSet = rtrim($sTobeUpdate, ', ');
        $this->sStatement = 'UPDATE ' . $sTableName . ' SET ' . $sSet;
        return $this;
    }

    /**
     * DELETE
     * @param  string   $sTableName  Table name
     * @return object
     */
    public function delete($sTableName)
    {
        $this->sStatement = 'DELETE FROM ' . $sTableName;
        return $this;
    }

    /**
     * COUNT
     * @param  array     $aParams       List of parameters
     * @return object
     */
    public function count($aParams = '*')
    {
        $this->sStatement = 'SELECT COUNT' . '(' . $aParams . ')';
        return $this;
    }

    /**
     * LIMIT
     * @param  integer   $iLimit   Limit
     * @return object
     */
    public function limit($iStart = 0, $iLimit = 10)
    {
        $this->sStatement .= ' LIMIT ' .  $iStart . ',' .$iLimit;
        return $this;
    }

    /**
     * OFFSET
     * @param  integer   $iOffset   Offset
     * @return object
     */
    public function offset($iOffset = 0)
    {
        $this->sStatement .= ' OFFSET ' . $iOffset;
        return $this;
    }

    /**
     * AND BETWEEN
     * @param  string $sParam   Parameters
     * @param  array  $aDates   Range of dates
     * @return object
     */
    public function andBetween($sParam, $aDates)
    {
        $this->sStatement .= ' AND ' . $sParam . ' BETWEEN ' . $aDates['start_date'] . ' AND ' . $aDates['end_date'];
        return $this;
    }

    /**
     * use to concat string into your sql statement
     * @param  [type] $sString [description]
     * @return [type]          [description]
     */
    public function concatQuery($sString)
    {
        $this->sStatement .= $sString;
        return $this;
    }

    /**
     * Encrypt Parameters
     * @param  array   $aParams    List of parameters that must be encrypted
     * @return array
     */
    private function encryptParameters($aParams)
    {
        if (libValid::isArray($aParams) === false) {
            return array(
                'fields' => '',
                'values' => ''
            );
        }

        $sFields = '';
        $sValues = '';
        foreach ($aParams as $mKey => $mValue) {
            $sFields = $sFields . ', ' . $mKey;
            $sValues = $sValues . ', HEX(AES_ENCRYPT(:' . $mKey . ',"' . libDatabaseConfig::KEY . '"))';
        }

        return array(
            'fields' => $sFields,
            'values' => $sValues
        );
    }

    /**
     * separate common data from data to be encrypted
     * @param  [type] $aParams [description]
     * @return [type]          [description]
     */
    public function separateEncrypData($aParams)
    {
        $aDataToEnc = array('applicant_name_kr', 'applicant_name_en', 'birthday', 'applicant_cell');
        $aEncrypt = array();

        foreach ($aDataToEnc as $sValue) {
            if (isset($aParams[$sValue]) === true) {
                $aEncrypt[$sValue] = $aParams[$sValue];
                unset($aParams[$sValue]);
            }
        }

        return array(
            'data'    => $aParams,
            'encrypt' => $aEncrypt
        );
    }
}
