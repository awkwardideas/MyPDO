<?php namespace AwkwardIdeas\MyPDO;

use AwkwardIdeas\MyPDO\DBConnection;
use AwkwardIdeas\MyPDO\SQLParameter;
use \PDO;

class MyPDO{
    private static $dbConnection;
    const DB_ErrorMessage = "Your request was not able to be completed due to a system error has occured";

    public function EstablishConnections($host, $dbname, $rUser, $rPwd, $rwUser, $rwPwd){
        try {
            //Establish Read Only Connection
            if (!isset($mysqlReader) || $mysqlReader == null) {
                $mysqlReader = new PDO("mysql:host=" . $host . ";dbname=" . $dbname, $rUser, $rPwd);
            }
            //Establish Read Write Connection
            if (!isset($mysqlAdmin) || $mysqlAdmin == null) {
                $mysqlAdmin = new PDO("mysql:host=" . $host . ";dbname=" . $dbname, $rwUser, $rwPwd);
            }
            self::$dbConnection = new DBConnection($mysqlReader, $mysqlAdmin);
            if (isset(self::$dbConnection->readOnly) && isset(self::$dbConnection->readWrite)) {
                return true;
            } else {
                return false;
            }
        }catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }

    public function CloseConnections(){
        self::$dbConnection = null;
    }

    public function Query($sqlCommand, $sqlParameters = null){
        try {
            $readOnly = self::$dbConnection->readOnly;
            $readOnly->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($sqlParameters != null) {
                $sqlQuery = $readOnly->prepare($sqlCommand);
                foreach ($sqlParameters as $sqlParameter) {
                    $sqlQuery->bindParam($sqlParameter->parameter, $sqlParameter->value, $sqlParameter->dataType);
                }
                $sqlQuery->execute();
                return $sqlQuery->fetchAll();
            } else {
                $sqlResponse = $readOnly->query($sqlCommand);
                return $sqlResponse->fetchAll();
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }

    public function QueryCount($sqlCommand, $sqlParameters = null){
        try {
            $readOnly = self::$dbConnection->readOnly;
            $readOnly->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if ($sqlParameters != null) {
                $sqlQuery = $readOnly->prepare($sqlCommand);
                foreach ($sqlParameters as $sqlParameter) {
                    $sqlQuery->bindParam($sqlParameter->parameter, $sqlParameter->value, $sqlParameter->dataType);
                }
                $sqlQuery->execute();
                return sizeof($sqlQuery->fetchAll());
            } else {
                $readOnly->query($sqlCommand);
                $foundRows = $readOnly->query("SELECT FOUND_ROWS()")->fetchColumn();
                return $foundRows;
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }

    public function Execute($sqlCommand, $sqlParameters = null){
        try {
            $readWrite = self::$dbConnection->readWrite;
            $readWrite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sqlQuery = $readWrite->prepare($sqlCommand);
            if ($sqlParameters != null) {
                foreach ($sqlParameters as $sqlParameter) {
                    $sqlQuery->bindParam($sqlParameter->parameter, $sqlParameter->value, $sqlParameter->dataType);
                }
            }
            $sqlQuery->execute();
            return true;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
    }

    function ExecuteGetIdentity($sqlCommand, $sqlParameters = null){
        try {
            $readWrite = self::$dbConnection->readWrite;
            $readWrite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sqlQuery = $readWrite->prepare($sqlCommand);
            if ($sqlParameters != null) {
                foreach ($sqlParameters as $sqlParameter) {
                    $sqlQuery->bindParam($sqlParameter->parameter, $sqlParameter->value, $sqlParameter->dataType);
                }
            }
            $sqlQuery->execute();
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }

        $selectIdentityCommand = "SELECT @@IDENTITY AS identity";
        $sqlQuery = $readWrite->prepare($selectIdentityCommand);
        $sqlQuery->execute();
        $identity = $sqlQuery->fetchAll();

        return $identity[0]["identity"];
    }
}