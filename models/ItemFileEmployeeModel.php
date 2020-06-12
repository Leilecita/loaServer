<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 16/12/2018
 * Time: 14:20
 */
require_once 'BaseModel.php';
class ItemFileEmployeeModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'items_employee_file';
    }

    function getMonthsGroup($paginator,$employee_id){
        $query='SELECT * FROM items_employee_file where employee_id = '.$employee_id.' group by MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);
    }

    function listAll($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC ';
        return $this->getDb()->fetch_all($query);
    }

    function amountHoursByMonthItem($filters){
        $conditions = join(' AND ',$filters);
        $query ='SELECT SUM(time_worked) AS total FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC ';
        $response = $this->getDb()->fetch_row($query);

        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }
}