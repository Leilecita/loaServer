<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 19/12/2018
 * Time: 17:37
 */
require_once 'BaseModel.php';

class ExtractionModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'extractions';
    }


    function getDaysGroup($paginator){

        $query='SELECT * FROM extractions group by DAY(created), MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

    function getMonthsGroup($paginator){

        $query='SELECT * FROM extractions group by MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

    function listAll($filters=array()){
        $conditions = join(' AND ',$filters);

        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC ';

        return $this->getDb()->fetch_all($query);
    }

    function amountByExtractionsDay2($filters){
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