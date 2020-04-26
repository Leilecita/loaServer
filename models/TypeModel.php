<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 26/04/2020
 * Time: 13:59
 */

require_once 'BaseModel.php';

class TypeModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'types';
    }

    function findAllTypes($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY name DESC ';
        return $this->getDb()->fetch_all($query);
    }

}