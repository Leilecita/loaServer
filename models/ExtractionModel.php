<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 19/12/2018
 * Time: 17:37
 */
require_once 'BaseModel.php';

class ExtractionModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'extractions';
    }



}