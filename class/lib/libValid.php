<?php

/**
 * validation library
 * @package class/lib
 * @author  정현 <hjung01@simplexi.com>
 * @version 1.0
 * @since   2016.07.21
 */
class libValid
{

    /**
     * validation date format (ex. 2015-11-31)
     * @param string $sDate
     * @return boolean
     */
    public static function isDate($sDate = '')
    {
        if ((preg_match('/^(\d{4})-(\d{2})-(\d{2})([ ](\d{2}):(\d{2}):(\d{2}))?$/', $sDate, $aMatch) && checkdate($aMatch[2], $aMatch[3], $aMatch[1])) === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * validation IP format (ex. 123.130.42.22)
     * @param string $sIp
     * @return boolean
     */
    public static function isIp($sIp = '')
    {
        if (preg_match('/^(([1-9]?\d|1\d{2}|2[0-4]\d|25[0-5]).){3}([1-9]?\d|1\d{2}|2[0-4]\d|25[0-5]|[*])$/', $sIp) === 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * validation array, array count > 0
     * @param mixed $mValue
     * @return bool
     * @static
     */
    public static function isArray($mValue)
    {
        return is_array($mValue) === true && count($mValue) > 0;
    }

    /**
     * validation string, string length > 0
     * @param mixed $mValue
     * @return bool
     * @static
     */
    public static function isString($mValue)
    {
        return is_string($mValue) === true && strlen($mValue) > 0;
    }

    /**
     * validation integer, value > 0
     * @param mixed $mValue
     * @return bool
     * @static
     */
    public static function isInt($mValue)
    {
        return is_int($mValue) === true && $mValue > 0;
    }

    /**
     * validation numeric, value > 0
     * @param mixed $mValue
     * @return bool
     * @static
     */
    public static function isNumeric($mValue)
    {
        return is_numeric($mValue) === true && (int)$mValue > 0;
    }

    /**
     * substitution numeric index to string key
     * @param array  $aData
     * @param string $sKey
     * @param bool   $bDuplicate duplicate status
     * @return array
     * @static
     */
    public static function getArrayKeyPivot($aData, $sKey, $bDuplicate = false)
    {
        if (is_array($aData) !== true) {
            return $aData;
        }
        $aReturn = array();
        foreach ($aData as $aVal) {
            if (array_key_exists($sKey, $aVal) === true) {
                if ($bDuplicate === false) {
                    $aReturn[$aVal[$sKey]] = $aVal;
                } else {
                    $aReturn[$aVal[$sKey]][] = $aVal;
                }
            }
        }
        return $aReturn;
    }

    /**
     * Check if the value is null
     * @param  [type]  $mValue [description]
     * @return boolean         [description]
     */
    public function isNull($mValue)
    {
        return is_null($mValue) === true && strlen($mValue) > 0;
    }

    /**
     * validation JSON type
     * @param $string
     * @return bool
     */
    public static function isJson($string)
    {
        return is_string($string) && is_object(json_decode($string));
    }

    /**
     * validation image extension
     * @param string $sFileName
     * @return bool
     */
    public static function isValidImage($sFileName)
    {
        $aImageExtPack = array(
            'gif',
            'jpg',
            'jpeg',
            'png',
            'bmp'
        );
        $aInfo = pathinfo($sFileName);
        $sImgExtension = strtolower($aInfo['extension']);
        return in_array($sImgExtension, $aImageExtPack, true);
    }

    /**
     * CAPI 결과 검증 및 리턴 api클래스용
     * @param array $aCapiReturn
     * @param bool  $bArrange
     * @return array
     * @throws libException
     */
    public static function capiReturnValid($aCapiReturn, $bArrange = false)
    {
        //배열인지 확인
        if (self::isArray($aCapiReturn) === false) {
            return array('code' => 400, 'msg' => 'CAPI 통신 오류', 'result' => false);
        }
        //리턴코드 정상인지 확인
        if ($aCapiReturn['meta']['code'] !== 200) {
            return array('code' => 400, 'msg' => 'CAPI API 호출 실패', 'result' => false);
        }
        if ($bArrange === false) {
            return $aCapiReturn['response']['result'];
        }
        //결과값 없음
        if (self::isArray($aCapiReturn['response']['result']) === false) {
            return array();
        }
        $aReturn = array();
        foreach ($aCapiReturn['response']['result'] as $aCapiData) {
            $aReturn[] = $aCapiData;
        }
        return $aReturn;
    }

    /**
     * Validate the inputs of user
     * @param  [type] $aParams [description]
     * @return [type]          [description]
     */
    public function htmlEntities($aParams)
    {
        foreach ($aParams as $sKey => $sValue) {
            htmlentities($sValue);
        }
        return $aParams;
    }

    public function escapeString($aParams)
    {
        foreach ($aParams as $sKey => $mValue) {
            if (libValid::isArray($mValue) === true) {
                foreach ($mValue as $iiKey => $sValue) {
                    $aParams[$sKey][$iiKey] = addslashes($sValue);
                }
            } else {
                $aParams[$sKey] = addslashes($mValue);
            }
        }
        return $aParams;
    }
}
