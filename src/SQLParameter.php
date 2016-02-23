<?php namespace AwkwardIdeas\MyPDO;
use \PDO;
class SQLParameter{
    public $parameter;
    public $value;
    public $dataType;

    public function __construct($parameter, $value, $dataType = "string"){
        $this->parameter = $parameter;
        $this->value = $value;
        switch ($dataType) {
            case "string":
                $this->dataType = PDO::PARAM_STR;
                break;
            case "int":
            case "integer":
                $this->dataType = PDO::PARAM_INT;
                break;
            case "bool":
                $this->dataType = PDO::PARAM_BOOL;
                break;
            case "null":
                $this->dataType = PDO::PARAM_NULL;
                break;
            case "datetime":$this->dataType = PDO::PARAM_STR;
                $this->value = date('Y-m-d H:i:s', strtotime($value));
                break;
            default: $this->dataType = PDO::PARAM_STR;
        }
    }
}