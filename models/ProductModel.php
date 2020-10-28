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

    function findAllProductsJoinPrevPrices($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT *,pe.id as event_price_id, p.id as product_id, p.created as product_created, pe.created as event_price_created FROM products as p JOIN price_events as pe ON p.id = pe.product_id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY event_price_created DESC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);
    }

    function getProductByBrands(){
        $query='SELECT * FROM products group by brand order by created desc ';
        return $this->getDb()->fetch_all($query);
    }

    function getProductsByDistintctItem(){
        $query='SELECT DISTINCT item from products order by created asc ';
        return $this->getDb()->fetch_all($query);
    }


    function getProductsByDistintctType($filters=array()){
        $conditions = join(' AND ',$filters);
        $query='SELECT DISTINCT p.type , color from products p, types t '.( empty($filters) ? '' : ' WHERE '.$conditions ).'  order by p.type desc ';
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
        $query = 'SELECT DISTINCT p.brand , color from products p, brands b '.( empty($filters) ? '' : ' WHERE '.$conditions ).' ORDER BY p.brand ASC';
        return $this->getDb()->fetch_all($query);
    }

    function getSpinnerTypes($filters=array()){
        $conditions = join(' AND ',$filters);
        // $query = 'SELECT DISTINCT '.$type.' FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions );
        $query = 'SELECT DISTINCT p.type , color from products p, types t '.( empty($filters) ? '' : ' WHERE '.$conditions ).' ORDER BY p.type ASC';
        return $this->getDb()->fetch_all($query);
    }



    function getSpinnerModel($filters=array(),$type){
        $conditions = join(' AND ',$filters);

        // $query = 'SELECT DISTINCT '.$type.' FROM '.$this->tableName;
        $query = 'SELECT DISTINCT '.$type.' FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY '.$type.' ASC';
        return $this->getDb()->fetch_all($query);
    }


}