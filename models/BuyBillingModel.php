<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 19/04/2021
 * Time: 20:22
 */

require_once 'BaseModel.php';

class BuyBillingModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'buy_billings';
    }


    function findAllBillings($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY billing_date DESC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);
    }



    function sumCantArt($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(art_cant) as total FROM buy_billings '.( empty($filters) ?  '' : ' WHERE '.$conditions );
        $response=$this->getDb()->fetch_row($query);
        if($response['total'] != null){
            return $response['total'];
        }else{
            $response['total']=0;
            return   $response['total'];
        }
    }
    function sumTotAmount($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(amount) as total FROM buy_billings '.( empty($filters) ?  '' : ' WHERE '.$conditions );
        $response=$this->getDb()->fetch_row($query);
        if($response['total'] != null){
            return $response['total'];
        }else{
            $response['total']=0;
            return   $response['total'];
        }
    }
}