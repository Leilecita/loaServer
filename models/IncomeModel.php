<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 22/11/2019
 * Time: 14:14
 */

require_once 'BaseModel.php';

class IncomeModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'incomes';
    }

    function getDaysGroup($paginator){

        $query='SELECT * FROM incomes group by DAY(created), MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

    function getMonthsGroup($paginator){

        $query='SELECT * FROM incomes group by MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

    function listAll($filters=array()){
        $conditions = join(' AND ',$filters);

        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC ';

        return $this->getDb()->fetch_all($query);
    }

    function amountByIncomesDay($filters){
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


    function findAllIncomesEvents($date,$dateTo){

        $query = 'SELECT * FROM incomes WHERE created < \''.$dateTo.'\' and created >= \''.$date.'\' ORDER BY created DESC';

        return $this->getDb()->fetch_all($query);
    }


    function findAllIncomes($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC';

        return $this->getDb()->fetch_all($query);
    }


    function amountByDateEf($date1,$date2,$payment_method){
        $response = $this->getDb()->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' WHERE created >= ? AND created < ? AND payment_method = ? AND detail = ? ORDER BY created DESC',$date1,$date2,$payment_method,"Salida venta");
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    function amountByDateCardDeb($date1,$date2,$payment_method){
        $response = $this->getDb()->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' WHERE created >= ? AND created < ? AND payment_method != ? AND detail = ? ORDER BY created DESC',$date1,$date2,$payment_method,"Salida venta");
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }
}