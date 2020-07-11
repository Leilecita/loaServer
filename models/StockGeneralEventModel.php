<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 09/07/2020
 * Time: 11:58
 */


require_once 'BaseModel.php';
class StockGeneralEventModel extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'stock_general_events';
    }

    function getProductGroupBy(){

        $query='SELECT *, SUM(stock) as total FROM products group by item , brand order by created desc ';
        return $this->getDb()->fetch_all($query);
    }

    function getAllGeneralEvents($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM stock_general_events '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC
        LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

}