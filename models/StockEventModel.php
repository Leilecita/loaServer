<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 05/04/2019
 * Time: 14:40
 */

require_once 'BaseModel.php';
class StockEventModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'stock_events';
    }


  /*  function getAllOrders($filters=array(),$paginator=array()){

        $conditions = join(' AND ',$filters);
        // $query = 'SELECT *, o.id as order_id FROM orders o JOIN clients c ON o.client_id = c.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY o.'.$order_state.' DESC, o.created DESC , o.state_prepare DESC
        $query = 'SELECT *, o.id as order_id FROM orders o JOIN clients c ON o.client_id = c.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY o.delivery_date DESC
        LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }*/

    function getAllEvents($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT *, p.created as product_created FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY s.created DESC
        LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }
}