<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 22/12/2020
 * Time: 17:03
 */

require_once 'BaseModel.php';

class StudentModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'students';
    }

}