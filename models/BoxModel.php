<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 11/03/2019
 * Time: 16:22
 */


require_once 'BaseModel.php';

class BoxModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'item_box';
    }
}

