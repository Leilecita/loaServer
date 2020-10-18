<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 12/10/2020
 * Time: 13:09
 */
require_once 'BaseModel.php';

class PriceEventModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'price_events';
    }


    function getAllPriceEvents($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT *, p.created as product_created, e.created as price_event_created, e.id as price_event_id FROM price_events e JOIN products p ON e.product_id = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY price_event_created DESC
        LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }


    function findAllEvents($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC';
        return $this->getDb()->fetch_all($query);
    }

}