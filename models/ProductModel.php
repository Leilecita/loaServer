<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 05/04/2019
 * Time: 12:20
 */

require_once 'BaseModel.php';
class ProductModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'products';
    }

    function getProductByBrands(){

        $query='SELECT * FROM products group by brand order by created desc ';
        return $this->getDb()->fetch_all($query);
    }

    function getProductByType(){

        $query='SELECT * FROM products p group by p.type order by created desc ';
        return $this->getDb()->fetch_all($query);
    }

    function findAllProducts($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC ';
        return $this->getDb()->fetch_all($query);
    }


    function getSpinnerBrands($filters=array()){
        $conditions = join(' AND ',$filters);
        // $query = 'SELECT DISTINCT '.$type.' FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions );
        $query = 'SELECT DISTINCT p.brand , color from products p, brands b '.( empty($filters) ? '' : ' WHERE '.$conditions );
        return $this->getDb()->fetch_all($query);
    }

    function getSpinnerTypes($filters=array()){
        $conditions = join(' AND ',$filters);
        // $query = 'SELECT DISTINCT '.$type.' FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions );
        $query = 'SELECT DISTINCT p.type , color from products p, types t '.( empty($filters) ? '' : ' WHERE '.$conditions );
        return $this->getDb()->fetch_all($query);
    }


}