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

    function findAll($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);
    }


    function save($data){
        if(empty($data['imageData'])) {
            unset($data['imageData']);
            return $this->getDb()->insert($this->tableName, $data);
        }else{
            $filePath = '/uploads/users/'.time().'.jpg';
            $this->base64_to_jpeg($data['imageData'],__DIR__.'/..'.$filePath);
            unset($data['imageData']);
            $data['image_url'] = $filePath;

            return $this->getDb()->insert($this->tableName, $data);
        }
    }
    function update($id,$data){

        error_log(print_r($data,true));
        if(empty($data['imageData'])) {
            unset($data['imageData']);

            return $this->getDb()->update($this->tableName, $data,['id' => "$id"]);
        }else{
            $filePath = '/uploads/users/'.time().'.jpg';
            $this->base64_to_jpeg($data['imageData'],__DIR__.'/..'.$filePath);
            unset($data['imageData']);
            $data['image_url'] = $filePath;

            return $this->getDb()->update($this->tableName, $data,['id' => "$id"]);
        }
    }


    function amountSaleByMonth($date1,$date2){
        $response = $this->getDb()->fetch_row('SELECT SUM(counted_sale) AS total FROM '.$this->tableName.' WHERE created >= ? AND created < ? ORDER BY created DESC',$date1,$date2);
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    function amountCardByMonth($date1,$date2){
        $response = $this->getDb()->fetch_row('SELECT SUM(credit_card) AS total FROM '.$this->tableName.' WHERE created >= ? AND created < ? ORDER BY created DESC',$date1,$date2);
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    function getAmountBoxByMonth($filters=array(),$paginator=array()){
        $query = 'SELECT sum(counted_sale) as sale,sum(credit_card) as card,sum(deposit) as dep, EXTRACT(YEAR FROM created) as y, EXTRACT(MONTH FROM created) as m FROM '.$this->tableName.'
        group by EXTRACT(YEAR FROM created), EXTRACT(MONTH FROM created) ORDER BY created DESC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];

        return $this->getDb()->fetch_all($query);
    }

}

