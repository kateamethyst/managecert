<?php


class libUtil
{
    /**
     * check is devenv
     * @return bool
     * @static
     */
    public static function isDevEnv()
    {
        preg_match('/app-sdk-[0-9]*\.cafe24\.com/', $_SERVER['SERVER_NAME'], $aMatch);
        return (count($aMatch) > 0 || libUtil::isLocal() === true);
    }

    /**
     * check if in local env
     * @return boolean [description]
     */
    public static function isLocal()
    {
        preg_match('/appsdk.com/', $_SERVER['SERVER_NAME'], $aMatch);
        return (count($aMatch) > 0);
    }

    /**
     * check if running in cstoredemo
     * @return boolean [description]
     */
    public static function isCstoreDemo()
    {
        preg_match('/cstoredemoph.cafe24\.com/', $_SERVER['SERVER_NAME'], $aMatch);
        return (count($aMatch) > 0);
    }

        /**
     * This function is part of the array_column library
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     *
     * @copyright Copyright (c) Ben Ramsey (http://benramsey.com)
     * @license http://opensource.org/licenses/MIT MIT
     *
     * Returns the values from a single column of the input array, identified by
     * the $sColumnKey.
     *
     * Optionally, you may provide an $sIndexKey to index the values in the returned
     * array by the values from the $sIndexKey column in the input array.
     *
     * @param array $aInput A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $sColumnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $sIndexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    public static function arrayColumn($aInput = null, $sColumnKey = null, $sIndexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        //
        // CODE HAS BEEN MODIFIED!!

        if (in_array(false, self::validateColumns($aInput, array('isset' => true, 'is_array' => true))) === true) {
            return $aInput;
        }

        $iCounter = count(
            array_filter(self::validateColumns(
                    $sColumnKey,
                    array(
                        'isset'     => true,
                        'is_int'    => true,
                        'is_float'  => true,
                        'is_string' => true
                    )
                )
            )
        );

        if (is_object($sColumnKey) === true || method_exists($sColumnKey, '__toString') === true) {
            $iCounter = 4;
        }

        if ($iCounter >= 4 || $iCounter === 0) {
            return $aInput;
        }

        $iNumericCounter = (is_int($sColumnKey) === true && is_float($sColumnKey) === true);

        $sColumnKey = (string)$sColumnKey;
        $aData = array();

        if (isset($sIndexKey) === true) {
            $aData['sParamsIndexKey'] = $iNumericCounter === true ? (int)$sIndexKey : (string)$sIndexKey;
        }

        $resultArray = array();

        foreach ($aInput as $mValue) {
            $aData['sKey'] = null;
            $aData['mValueSet'] = false;
            $aData['sKeySet'] = $aData['mValueSet'];
            $bValueIsArray = is_array($mValue);

            if ($bValueIsArray === true) {
                $aUpdatedData = self::updateColumns($aData, $mValue, $sColumnKey);
                $aData = $aUpdatedData['aData'];
                $mValue = $aUpdatedData['mValue'];
            }

            if ($aData['mValueSet'] === true) {
                if ($aData['sKeySet'] === true) {
                    $resultArray[$aData['sKey']] = $aUpdatedData['mValue'];
                } else {
                    $resultArray[] = $aUpdatedData['mValue'];
                }
            }
        }

        return $resultArray;
    }

    /**
     * updates the column keys
     * @param  array  $aKeys         Key set
     * @param  mixed  $mValue        Value
     * @param  string $sColumnKey    Index/key to search/update
     * @return array                 Updated Array
     */
    private function updateColumns($aKeys, $mValue, $sColumnKey)
    {
        if (array_key_exists($aKeys['sParamsIndexKey'], $mValue) === true) {
            $aKeys['sKeySet'] = true;
            $aKeys['sKey'] = (string)$mValue[$aKeys['sParamsIndexKey']];
        }

        if (array_key_exists($sColumnKey, $mValue) === true) {
            $aKeys['mValueSet'] = true;
            $mValue = $mValue[$sColumnKey];
        }

        return array(
            'aData'  => $aKeys,
            'mValue' => $mValue
        );
    }

    /**
     * Validate if inputs are valid
     * @param  [type] $mInput     [description]
     * @param  array  $aValidator [description]
     * @return [type]             [description]
     */
    private function validateColumns($mInput, $aValidator = array('isset' => true))
    {
        $aResult = array();

        foreach ($aValidator as $sFunction => $bExpected) {
            // $mInput is used here
            array_push($aResult, eval('return ' . $sFunction . '($mInput);') === $bExpected);
        }

        return $aResult;
    }
}
