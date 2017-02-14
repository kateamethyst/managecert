<?php

class modelCommon
{
    protected $oDBInstance;

    protected function getDBConnection()
    {
        if (($this->oDBInstance instanceof PDO) === false) {
            try {
                if (libUtil::isDevEnv() === true || libUtil::isCstoreDemo() === true) {
                    $sHost = libDatabaseConfig::DATABASE_HOST_DEV;
                } else if (libUtil::isLocal() === true) {
                    $sHost = 'localhost';
                } else {
                    $sHost = libDatabaseConfig::DATABASE_HOST;
                }

                $this->oDBInstance = new PDO
                (
                    'mysql:host=' . $sHost . ';dbname=' .  libDatabaseConfig::DATABASE_NAME,
                    libDatabaseConfig::DATABASE_USERNAME,
                    libDatabaseConfig::DATABASE_PASSWORD
                );
                $this->oDBInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $sErrors) {
                return $sErrors->getMessage();
            }
        }

        if ( version_compare(PHP_VERSION, '5.3.6', '<') && ! defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
                $sql = 'SET NAMES utf8';
                $this->oDBInstance->exec($sql);
        }

        return $this->oDBInstance;
    }

    protected function closeConnection()
    {
        $this->oDBInstance = null;
    }


    /**
     * getting total count of result
     * @return int [description]
     */
    protected function getTotalCount()
    {
        $oQuery = $this->oDBInstance->prepare('SELECT FOUND_ROWS() as COUNT');
        $oQuery->execute();
        $aTotal = $oQuery->fetch(PDO::FETCH_ASSOC);

        return (int)$aTotal['COUNT'] ?: 0;
    }
}

