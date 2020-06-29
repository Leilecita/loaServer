<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 25/04/2019
 * Time: 16:18
 */

require_once 'BaseModel.php';


class UserModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'users';
    }

}