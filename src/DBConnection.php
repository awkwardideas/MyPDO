<?php namespace AwkwardIdeas\MyPDO;

class DBConnection{
    public $readOnly;
    public $readWrite;
    public function __construct($readOnly, $readWrite){
        $this->readOnly = $readOnly;
        $this->readWrite = $readWrite;
    }
}