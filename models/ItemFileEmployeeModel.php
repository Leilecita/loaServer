<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 16/12/2018
 * Time: 14:20
 */
require_once 'BaseModel.php';
class ItemFileEmployeeModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'items_employee_file';
    }

}