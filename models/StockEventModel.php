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
        $query = 'SELECT *, p.created as product_created, s.created as stock_event_created, s.id as stock_event_id FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY stock_event_created DESC
        LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

    function getEvent($id_stock_event){
        $query = 'SELECT *, p.created as product_created, s.created as stock_event_created, s.id as stock_event_id FROM stock_events s INNER JOIN products p ON '.$id_stock_event.' = s.id ';
        return $this->getDb()->fetch_row($query);

    }


    function amountSaleByDate($date1,$date2){
        $response = $this->getDb()->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' WHERE created >= ? AND created < ? ORDER BY created DESC',$date1,$date2);
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    function amountSaleByDateEf($date1,$date2,$payment_method){
        $response = $this->getDb()->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' WHERE created >= ? AND created < ? AND payment_method = ? ORDER BY created DESC',$date1,$date2,$payment_method);
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    function amountSaleByDateCardDeb($date1,$date2,$payment_method){
        $response = $this->getDb()->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' WHERE created >= ? AND created < ? AND payment_method != ? ORDER BY created DESC',$date1,$date2,$payment_method);
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }
}