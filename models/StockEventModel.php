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

    function getDistinctsEventsDetail($filters){
        $conditions = join(' AND ',$filters);
        $query='SELECT DISTINCT detail from stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' order by detail desc ';
        return $this->getDb()->fetch_all($query);
    }



    function getEventsGroupByDayEntries($paginator){
        $query='SELECT * FROM stock_events where detail like '.'"%ingreso compra%"'.' group by DAY(created), MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);
    }

    function getEventsGroupByMonthEntries($paginator){
        $query='SELECT * FROM stock_events where detail like '.'"%ingreso compra%"'.' group by MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);
    }

    function getEventsGroupByDay($paginator){
       // $query='SELECT * FROM stock_events group by DAY(created), MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
       // $query="(".'SELECT id,created FROM stock_events group by DAY(created), MONTH(created), YEAR (created)'.")".'UNION '."(".' SELECT id,created FROM items_file group by DAY(created), MONTH(created), YEAR (created)'.")".' order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        $query='SELECT id,created FROM '."(".'SELECT id,created as created FROM stock_events UNION ALL SELECT id,created as created FROM items_file '.")".' data group by DAY(created), MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);
    }

    function getEventsGroupByMonth($paginator){
        $query='SELECT * FROM stock_events group by MONTH(created), YEAR (created) order by created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);
    }


    // GROUP_CONCAT(CONCAT('{type:"', p.type, '", brand:"',p.brand,'"}')) list
   function getEventsGroupByDayFilters($paginator,$filters=array()){
        $conditions = join(' AND ',$filters);
        $query='SELECT s.created , GROUP_CONCAT( CONCAT("item",":",p.item,"type",":",p.type,",","brand",":",p.brand)) as list
        FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by DAY(s.created), MONTH(s.created), YEAR(s.created) order by s.created desc LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

   }

    function getAllEvents($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT *, p.created as product_created, s.created as stock_event_created, s.id as stock_event_id FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY stock_event_created DESC
        LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

    /*
     * array('item' => $list[$i]['item'],'type' => $list[$i]['type'],'brand' => $list[$i]['brand'],'model' => $list[$i]['model'],
                'stock_in' => $list[$i]['stock_in'],'stock_out' => $list[$i]['stock_out'],'stock_event_created' => $list[$i]['stock_event_created'],
                'value' => $list[$i]['value'], 'payment_method'=> $list[$i]['payment_method'], 'detail'=> $list[$i]['detail'],'stock_event_id' => $list[$i]['stock_event_id'],
                'client_name' => $list[$i]['client_name']
     */

    function getAllEventsSale($filters=array()){
        $conditions = join(' AND ',$filters);
       // $query = 'SELECT *, p.created as product_created, s.created as stock_event_created, s.id as stock_event_id
        $query = 'SELECT *, p.type as type, p.brand as brand, p.model as model, s.stock_in as stock_in, s.stock_out as stock_out, s.value as value,
                  s.payment_method as payment_method, s.detail as detail, s.client_name as client_name,s.client_id as client_id ,s.observation as observation,
                   p.created as product_created, s.created as stock_event_created,s.today_created_client as today_created_client ,s.id as stock_event_id
        FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY stock_event_created DESC';
        return $this->getDb()->fetch_all($query);

    }

    function getAllEventsSaleByPage($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        // $query = 'SELECT *, p.created as product_created, s.created as stock_event_created, s.id as stock_event_id
        $query = 'SELECT *, p.type as type, p.brand as brand, p.model as model, s.stock_in as stock_in, s.stock_out as stock_out, s.value as value,
                  s.payment_method as payment_method, s.detail as detail, s.client_name as client_name,s.client_id as client_id ,s.observation as observation,
                   p.created as product_created, s.created as stock_event_created,s.today_created_client as today_created_client ,s.id as stock_event_id
        FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY stock_event_created DESC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);

    }

    function countStockEvents($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT COUNT(*) as total FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions );
        $response=$this->getDb()->fetch_row($query);
        if($response['total'] != null){
            return $response['total'];
        }else{
            $response['total']=0;
            return   $response['total'];
        }

    }

    function sumEntries($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock_in) as total FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions );
        $response=$this->getDb()->fetch_row($query);
        if($response['total'] != null){
            return $response['total'];
        }else{
            $response['total']=0;
            return   $response['total'];
        }
    }

    function sumStock($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock) as total FROM products '.( empty($filters) ?  '' : ' WHERE '.$conditions );
        $response=$this->getDb()->fetch_row($query);
        if($response['total'] != null){
            return $response['total'];
        }else{
            $response['total']=0;
            return   $response['total'];
        }
    }


    function sumSales($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock_out) as total FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions );
        $response=$this->getDb()->fetch_row($query);
        if($response['total'] != null){
            return $response['total'];
        }else{
            $response['total']=0;
            return   $response['total'];
        }
    }

    //statistics

    function sumSalesGroupByBrand($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock_out) as total, p.brand as descr, "" as descr2 FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.brand order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }

    function sumSalesAmountGroupByBrand($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(value) as total, p.brand as descr, "" as descr2 FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.brand order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }


    function sumEntriesGroupByBrand($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock_in) as total, p.brand as descr ,  "" as descr2 FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.brand order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }


    function sumStockGroupByBrand($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock) as total, p.brand as descr , "" as descr2 FROM products p '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.brand order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }

    function sumSalesGroupByArt($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock_out) as total, p.type as descr, "" as descr2 FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.type order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }

    function sumSalesAmountGroupByArt($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(value) as total, p.type as descr, "" as descr2 FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.type order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }


    function sumEntriesGroupByArt($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock_in) as total, p.type as descr, "" as descr2 FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.type order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }

    function sumStockGroupByArt($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock) as total, p.type as descr, "" as descr2 FROM products p '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.type order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }


    function sumSalesGroupByItemAndArt($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock_out) as total, p.item as descr, p.type as descr2 FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.item, p.type  order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }

    function sumSalesAmountGroupByItemAndArt($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(value) as total, p.item as descr, p.type as descr2 FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.item, p.type  order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }

    function sumEntriesGroupByItemAndart($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock_in) as total, p.item as descr, p.type as descr2 FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.type order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }

    function sumStockGroupByItemAndArt($filters=array(),$limit){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(stock) as total, p.item as descr, p.type as descr2 FROM products p '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' group by p.type order by total DESC LIMIT '.$limit;
        return $this->getDb()->fetch_all($query);
    }



    function sumAmountMoneySales($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(value) as total FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions );
        $response=$this->getDb()->fetch_row($query);
        if($response['total'] != null){
            return $response['total'];
        }else{
            $response['total']=0;
            return   $response['total'];
        }
    }

    function getEvent($filters=array()){
        $conditions = join(' AND ',$filters);

        $query = 'SELECT *, p.type as type, p.brand as brand, p.model as model, s.stock_in as stock_in, s.stock_out as stock_out, s.value as value,
                  s.payment_method as payment_method, s.detail as detail, s.client_name as client_name, s.observation as observation,
                   p.created as product_created, s.created as stock_event_created, s.id as stock_event_id 
        FROM stock_events s JOIN products p ON s.id_product = p.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY stock_event_created DESC';
        return $this->getDb()->fetch_row($query);
        //$query = 'SELECT *, p.created as product_created, s.created as stock_event_created, s.id as stock_event_id FROM stock_events s INNER JOIN products p ON '.$id_stock_event.' = s.id ';
        //return $this->getDb()->fetch_row($query);

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

    function amountSaleByDateByMethodPaymentSales($date1, $date2, $payment_method){
        $response = $this->getDb()->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' WHERE created >= ? AND created < ? AND payment_method = ? ORDER BY created DESC',$date1,$date2,$payment_method);
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    function amountSaleByDateByMethodPaymentOnlySales($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT SUM(value) as total FROM stock_events '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC';
        $response=$this->getDb()->fetch_row($query);
        if($response['total'] != null){
            return $response['total'];
        }else{
            $response['total']=0;
            return   $response['total'];
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