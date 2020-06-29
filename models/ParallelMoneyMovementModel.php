<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 18/06/2020
 * Time: 15:40
 */

require_once 'BaseModel.php';

class ParallelMoneyMovementModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'parallel_money_movements';
    }


    function getDaysGroup($paginator){

        $query='SELECT * FROM parallel_money_movements group by DAY(created), MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

    function getMonthsGroup($paginator){

        $query='SELECT * FROM parallel_money_movements group by MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

    function listAll($filters=array()){
        $conditions = join(' AND ',$filters);

        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC ';

        return $this->getDb()->fetch_all($query);
    }

    function amountByDay($filters){
        $conditions = join(' AND ',$filters);
        $query ='SELECT SUM(value) AS total FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC ';
        $response = $this->getDb()->fetch_row($query);

        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

}