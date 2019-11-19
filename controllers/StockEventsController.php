<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 05/04/2019
 * Time: 14:41
 */

require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/StockEventModel.php';
require_once  __DIR__.'/../models/ProductModel.php';

class StockEventsController extends SecureBaseController
{
    private $products;
    function __construct(){
        parent::__construct();
        $this->model = new StockEventModel();
        $this->products = new ProductModel();
    }


    function getFiltersEvents(){
        $filters= array();

        if(isset($_GET['since_s'])){

            $created=$_GET['since_s'];

            $parts = explode(" ", $created);
            $date=$parts[0]." 00:00:00";
            $filters[] = 's.created >= "'.$date.'"';
        }
        if(isset($_GET['to_s'])){

            $created=$_GET['to_s'];
            $parts = explode(" ", $created);
            $date=$parts[0]." 00:00:00";

            $filters[] = 's.created < "'.$date.'"';
        }

        if(isset($_GET['item'])) {
            if ($_GET['item'] != "Todos") {
                $filters[] = 'item = "' . $_GET['item'] . '"';
            }
        }

        return $filters;
    }



    function getStockEventsDay(){
        $res= $this->model->getAllEvents($this->getFiltersEvents(),$this->getPaginator());

        $report=array();
        for ($i = 0; $i < count($res); ++$i) {

            $report[]=array('item' => $res[$i]['item'],'type' => $res[$i]['type'],'brand' => $res[$i]['brand'],'model' => $res[$i]['model'],
                'stock_in' => $res[$i]['stock_in'],'stock_out' => $res[$i]['stock_out'],'stock_event_created' => $res[$i]['stock_event_created'],
                'value' => $res[$i]['value'], 'payment_method'=> $res[$i]['payment_method'], 'detail'=> $res[$i]['detail'],'stock_event_id' => $res[$i]['stock_event_id']);
        }

        $this->returnSuccess(200,$report);
    }

    function updateStockEvent(){

        $stock_event=$this->model->findById($_GET['id']);

        if($stock_event){

            $this->model->update($_GET['id'],array('created' => $_GET['date']));
            $this->model->update($_GET['id'],array('value' => $_GET['value']));
            $this->model->update($_GET['id'],array('payment_method' => $_GET['payment_method']));
            $this->model->update($_GET['id'],array('detail' => $_GET['detail']));

            //DEVUELVE SOLO UN EVENTO DE STOCK JOIN CON PRODUCTO_ID
            $repot_stock_event=$this->model->getEvent($_GET['id']);

            $report=array('item' => $repot_stock_event['item'],'type' => $repot_stock_event['type'],'brand' => $repot_stock_event['brand'],'model' => $repot_stock_event['model'],
                'stock_in' =>$repot_stock_event['stock_in'],'stock_out' => $repot_stock_event['stock_out'],'stock_event_created' => $repot_stock_event['stock_event_created'],
                'value' => $repot_stock_event['value'], 'payment_method'=> $repot_stock_event['payment_method'], 'detail'=> $repot_stock_event['detail'],'stock_event_id' => $repot_stock_event['stock_event_id']);
            $this->returnSuccess(200,$report);
        }else{
            $this->returnError(400, "entity not found");
        }
    }

    function getAmountSaleByDate(){

        $created=$_GET['created'];

        $parts = explode(" ", $created);
        $date=$parts[0]." 00:00:00";
        $next_date = date('Y-m-d', strtotime( $parts[0].' +1 day'));
        $dateTo=$next_date." 00:00:00";
        $filters= array();
        $filters[] = 'created >= "'.$date.'"';
        $filters[] = 'created < "'.$dateTo.'"';

        $totalAmount=$this->getModel()->amountSaleByDateEf($date,$dateTo,"efectivo");

        $this->returnSuccess(200,$totalAmount);
    }

    function getAmountSaleByDateCard(){

        $created=$_GET['created'];

        $parts = explode(" ", $created);
        $date=$parts[0]." 00:00:00";
        $next_date = date('Y-m-d', strtotime( $parts[0].' +1 day'));
        $dateTo=$next_date." 00:00:00";
        $filters= array();
        $filters[] = 'created >= "'.$date.'"';
        $filters[] = 'created < "'.$dateTo.'"';

        $totalAmount=$this->getModel()->amountSaleByDateCardDeb($date,$dateTo,"efectivo");

        $this->returnSuccess(200,$totalAmount);
    }

    function getBalance(){
        $this->returnSuccess(200,$this->getModel()->findAll($this->getFilters(),$this->getPaginator()));
    }


    function post(){
        $data = (array)json_decode(file_get_contents("php://input"));
        $this->updateStockProduct($data['id_product'],$data['stock_in'],$data['stock_out']);

        unset($data['id']);
        $res = $this->getModel()->save($data);

        if($res<0){
            $this->returnError(404,null);
        }else{
            $inserted = $this->getModel()->findById($res);

            if($_GET['balance'] == "balance"){
                $this->loadIdealStock($inserted);
            }
            $this->returnSuccess(201,$inserted);
        }
    }
    
    function loadIdealStock($inserted){

        $filters= array();
        $filters[] = 'id_product = "'.$inserted['id_product'].'"';
        $stock_events= $this->getModel()->findAllByDate($filters);

        if(count($stock_events) > 1){
            $last=$stock_events[1]['ideal_stock'];
            $this->model->update($inserted['id'],array('ideal_stock'=> $last));
        }
    }




    function updateStockProduct($id,$valueIn,$valueOut){

       $prod= $this->products->findById($id);
        if($prod){
            $val=$valueIn + $prod['stock'] - $valueOut;
            $this->products->update($id,array('stock'=> $val ));
        }
    }
}