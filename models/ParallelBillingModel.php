<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 20/11/2020
 * Time: 15:53
 */

require_once 'BaseModel.php';

class ParallelBillingModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'parallel_billings';
    }

    function getDistinctTypes(){
        $query='SELECT DISTINCT type from parallel_billings order by created asc ';
        return $this->getDb()->fetch_all($query);
    }

    function getDaysGroup($paginator){

        $query='SELECT * FROM parallel_money_movements group by DAY(created), MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

    function getMonthsGroup($paginator){

        $query='SELECT * FROM parallel_billings group by MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

    function listAll($filters=array()){
        $conditions = join(' AND ',$filters);

        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC ';

        return $this->getDb()->fetch_all($query);
    }

    function amountMoney($filters){
        $conditions = join(' AND ',$filters);
        $query ='SELECT SUM(amount) AS total FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC ';
        $response = $this->getDb()->fetch_row($query);

        if($response['total']!=null){
            return $response['total'];
        }else{
            $response['total']=0;
            return $response['total'];
        }
    }

}