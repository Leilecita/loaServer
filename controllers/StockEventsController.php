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
require_once  __DIR__.'/../models/ItemFileModel.php';
require_once  __DIR__.'/../models/IncomeModel.php';

class StockEventsController extends SecureBaseController
{
    private $products;
    private $items_file;
    private $incomes;
    function __construct(){
        parent::__construct();
        $this->model = new StockEventModel();
        $this->products = new ProductModel();
        $this->items_file = new ItemFileModel();
        $this->incomes = new IncomeModel();
    }

    function getDatesMonth($data){

        $partsYearMonthDay=explode("-", $data);
        $partsYearMonthDay[2]=01;

        $date= $partsYearMonthDay[0]."-".$partsYearMonthDay[1]."-".$partsYearMonthDay[2]." 00:00:00";

        $next_month = date('Y-m-d', strtotime( $date.' +1 month'));
        $dateTo=$next_month." 00:00:00";
        $result=array('date' => $date, 'dateTo' => $dateTo);

        return $result;
    }

    function getDates($data){

        $parts = explode(" ", $data);
        $date=$parts[0]." 00:00:00";
        $next_date = date('Y-m-d', strtotime( $parts[0].' +1 day'));
        $dateTo=$next_date." 00:00:00";
        $result=array('date' => $date, 'dateTo' => $dateTo);
        return $result;
    }




   function getItemFileClientReport($listItemsFile){

       for ($i = 0; $i < count($listItemsFile); ++$i) {
           if($listItemsFile[$i]['value'] >= 0){ //sino solo se esta registrando la deuda de la ficha
               $report[]=array('name' => $listItemsFile[$i]['name'],'value' => $listItemsFile[$i]['value'],'item_file_created' => $listItemsFile[$i]['item_file_created'],
                   'description' => $listItemsFile[$i]['description'],'payment_method' => $listItemsFile[$i]['payment_method']);
           }
       }
   }

   function filterSum($dates){
       $filters=array();

       $filters[] = 's.created >= "'.$dates['date'].'"';
       $filters[] = 's.created < "'.$dates['dateTo'].'"';

       return $filters;
   }

    function filters($dates){

        $filters=array();

        if(isset($_GET['item'])) {
            if ($_GET['item'] != "Todos") {
                $filters[] = 'item = "' . $_GET['item'] . '"';
            }
        }

        $filters[] = 's.created >= "'.$dates['date'].'"';
        $filters[] = 's.created < "'.$dates['dateTo'].'"';

        return $filters;
    }

    function filterSale($filters){
        $filters[] = '(detail like "%'."salida".'%" OR detail like "%'."Ingreso dev".'%")';
        return $filters;
    }

    function filterEntrie($filters){
        $filters[] = 'detail like "%'."ingreso compra".'%"';
        return $filters;
    }

    function getEvents(){

        $list=$this->model->getAllEvents($this->getFilters(),$this->getPaginator());
        $listReport=$this->getStockEventReport($list);

        $this->returnSuccess(200,$listReport);


    }

    function getEntries(){

        if($_GET['groupby'] === "month"){
            $days=$this->model->getEventsGroupByMonthEntries($this->getPaginator());
        }else{
            $days=$this->model->getEventsGroupByDayEntries($this->getPaginator());
        }

        $reportDay=array();

        for ($i = 0; $i < count($days); ++$i) {

            if($_GET['groupby'] === "month"){
                $dates=$this->getDatesMonth($days[$i]['created']);
            }else{
                $dates=$this->getDates($days[$i]['created']);
            }

            //$listStockEventsByEntries= $this->model->getAllEventsSale($this->filterEntrie($this->filters($dates)));

            $reportStockEventByEntries=$this->model->getAllEventsSale($this->filterEntrie($this->filters($dates)));

            $sumEntries= $this->model->sumEntries($this->filterEntrie($this->filters($dates)));

            $reportDay[]=array('created'=>$days[$i]['created'],'listEntries' => $reportStockEventByEntries,'countEntries' => $sumEntries);
        }
             $this->returnSuccess(200,$reportDay);

    }
    function getStockEventReport($list){
        $report=array();
        for ($i = 0; $i < count($list); ++$i) {
            $report[]=array('item' => $list[$i]['item'],'type' => $list[$i]['type'],'brand' => $list[$i]['brand'],'model' => $list[$i]['model'],
                'stock_in' => $list[$i]['stock_in'],'stock_out' => $list[$i]['stock_out'],'stock_event_created' => $list[$i]['stock_event_created'],
                'value' => $list[$i]['value'], 'payment_method'=> $list[$i]['payment_method'], 'detail'=> $list[$i]['detail'],'stock_event_id' => $list[$i]['stock_event_id'],
                'client_name' => $list[$i]['client_name'],'observation' => $list[$i]['observation']);
        }
        return $report;
    }


    function getPaginatorSales(){
        $paginator = array('offset' => 0, 'limit' => PAGE_SIZE);
        if(isset($_GET['page'])){
            $paginator['offset'] = 1 * $_GET['page'];
        }
        return $paginator;
    }

    function getSales2Prueba(){
        $reportStockEventsBySale=$this->model->getEventsGroupByDayFilters($this->getPaginatorSales(),($this->filterSale(array())));
        $array=array();
        for ($i = 0; $i < count($reportStockEventsBySale); ++$i) {

            $porciones = explode(",", $reportStockEventsBySale[$i]['list']);
           // var_dump($porciones);


          //  $json[]=json_encode($reportStockEventsBySale[$i]['list']);
        }

        //$data = (array)json_decode($reportStockEventsBySale['list']);

        $this->returnSuccess(200,$reportStockEventsBySale);

    }

    function getSales(){

        if($_GET['groupby'] === "month"){
            $days=$this->model->getEventsGroupByMonth($this->getPaginatorSales());
        }else{
            $days=$this->model->getEventsGroupByDay($this->getPaginatorSales());
        }

       $reportDay=array();
       for ($i = 0; $i < count($days); ++$i) {

           if($_GET['groupby'] === "month"){
               $dates=$this->getDatesMonth($days[$i]['created']);
           }else{
               $dates=$this->getDates($days[$i]['created']);
           }

           $reportStockEventBySale=$this->model->getAllEventsSale($this->filterSale($this->filters($dates)));

           $reportItemsFile= $this->items_file->getItemsFileClientEvents($dates['date'],$dates['dateTo']);

           $efectAmount=$this->model->amountSaleByDateByMethodPayment($dates['date'],$dates['dateTo'],"efectivo");
           $transfAmount=$this->model->amountSaleByDateByMethodPayment($dates['date'],$dates['dateTo'],"transferencia");
           $mercPagAmount=$this->model->amountSaleByDateByMethodPayment($dates['date'],$dates['dateTo'],"mercado pago");


           $debitoAmount=$this->model->amountSaleByDateByMethodPayment($dates['date'],$dates['dateTo'],"debito");
           $creditAmount=$this->model->amountSaleByDateByMethodPayment($dates['date'],$dates['dateTo'],"tarjeta");


           $efectAmountItemsFileClientSales=$this->items_file->amountByDateEf($dates['date'],$dates['dateTo'],"efectivo");

           $cardAmountItemsFileClientCard=$this->items_file->amountByDateCardDeb($dates['date'],$dates['dateTo'],"efectivo");

           $totalEf=$efectAmount['total']+$efectAmountItemsFileClientSales['total'];

           $totalCard= $debitoAmount['total']+$creditAmount['total']+$cardAmountItemsFileClientCard['total'];

         //  $countSales= $this->model->countStockEvents($this->filterSale($this->filters($dates)));
           $countSales= $this->model->sumSales($this->filterSale($this->filters($dates)));

           $reportDay[]=array('created'=>$days[$i]['created'],'countSales' => $countSales, 'efectAmount' => $totalEf, 'cardAmount' => $totalCard ,
               'transfAmount'=>$transfAmount['total'], 'mercPagoAmount' => $mercPagAmount['total'],
               'listStockEventSale' => $reportStockEventBySale, 'listItems' => $reportItemsFile);
       }

       $this->returnSuccess(200,$reportDay);
   }

    function updateStockEvent(){

        $stock_event=$this->model->findById($_GET['id']);

        if($stock_event){

            $this->model->update($_GET['id'],array('created' => $_GET['date']));
            $this->model->update($_GET['id'],array('value' => $_GET['value']));
            $this->model->update($_GET['id'],array('payment_method' => $_GET['payment_method']));
            $this->model->update($_GET['id'],array('detail' => $_GET['detail']));

            //DEVUELVE SOLO UN EVENTO DE STOCK JOIN CON PRODUCTO_ID

           // $repot_stock_event=$this->model->getEvent($_GET['id']);

            $filters=array();
            $filters[] = 's.id = "'.$_GET['id'].'"';
            $repot_stock_event=$this->model->getEvent($filters);

            $report=array('item' => $repot_stock_event['item'],'type' => $repot_stock_event['type'],'brand' => $repot_stock_event['brand'],'model' => $repot_stock_event['model'],
                'stock_in' =>$repot_stock_event['stock_in'],'stock_out' => $repot_stock_event['stock_out'],'stock_event_created' => $repot_stock_event['stock_event_created'],
                'value' => $repot_stock_event['value'], 'payment_method'=> $repot_stock_event['payment_method'], 'detail'=> $repot_stock_event['detail'],'stock_event_id' => $repot_stock_event['stock_event_id']);
            $this->returnSuccess(200,$report);
        }else{
            $this->returnError(400, "entity not found");
        }
    }


    //TO DELETE
    function filterEventsOut($filters){
        $filters[] = 'detail like "%'."salida".'%"';
        return $filters;
    }

    function getStockEventsDay(){
        $res= $this->model->getAllEvents($this->filterEventsOut($this->getFiltersEvents()),$this->getPaginator());

        $report=array();
        for ($i = 0; $i < count($res); ++$i) {
            $report[]=array('item' => $res[$i]['item'],'type' => $res[$i]['type'],'brand' => $res[$i]['brand'],'model' => $res[$i]['model'],
                'stock_in' => $res[$i]['stock_in'],'stock_out' => $res[$i]['stock_out'],'stock_event_created' => $res[$i]['stock_event_created'],
                'value' => $res[$i]['value'], 'payment_method'=> $res[$i]['payment_method'], 'detail'=> $res[$i]['detail'],'stock_event_id' => $res[$i]['stock_event_id']);
        }

        $this->returnSuccess(200,$report);
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

        $filters[] = 'detail like "%'."salida".'%"';
        return $filters;
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

      //  $totalAmountItemsFileClientSales=$this->items_file->amountByDay($date,$dateTo);


        $totalAmountItemsFileClientSales=$this->items_file->amountByDateEf($date,$dateTo,"efectivo");

        $totalAmountIncomes= $this->incomes->amountByDateEf($date,$dateTo,"efectivo");

        $totalAmount=$this->model->amountSaleByDateByMethodPayment($date,$dateTo,"efectivo");

        $total=array('total' => $totalAmount['total']+$totalAmountItemsFileClientSales['total']+$totalAmountIncomes['total']);

        $this->returnSuccess(200,$total);
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


        //suma todos los que son distinto a efectivo
        $totalAmountItemsFileClientCard=$this->items_file->amountByDateCardDeb($date,$dateTo,"efectivo");

        //suma todos los que son distinto a efectivo
        $totalAmountIncomes=$this->incomes->amountByDateCardDeb($date,$dateTo,"efectivo");

        //suma todos los que son distinto a efectivo
        //POR EL MOMENTO LO DEJAMOS ASI
        $totalAmount=$this->model->amountSaleByDateCardDeb($date,$dateTo,"efectivo");

        $total=array('total' => $totalAmount['total']+$totalAmountItemsFileClientCard['total']+$totalAmountIncomes['total']);

        $this->returnSuccess(200,$total);
    }


    // fin to delete

    function getBalance(){
        $this->returnSuccess(200,$this->getModel()->findAll($this->getFilters(),$this->getPaginator()));
    }

    function checkClientId($client_id,$inserted,$value_for_file){
        if($client_id >= 0){
            $this->createItemFileByClientId($inserted,$client_id,$value_for_file);
        }
    }


    function createItemFileByClientId($inserted, $client_id,$value_for_file){

        $product=$this->products->findById($inserted['id_product']);

        $itemFile= array('client_file_id'=> 0,'client_id' => $client_id,'description' => $inserted['detail'], 'brand' => $product['brand'],
            'product_kind' => $product['item'],'value' =>$value_for_file*(-1),'retired_product' => "true", 'payment_method' =>$inserted['payment_method'],'settled' => "false");
        $res=$this->items_file->save($itemFile);
    }

    function post(){
        $data = (array)json_decode(file_get_contents("php://input"));


        $this->updateStockProduct($data['id_product'],$data['stock_in'],$data['stock_out']);

        $client_id=$data['client_id'];
        unset($data['client_id']);

        $value_for_file=$data['value_for_file'];
        unset($data['value_for_file']);

        unset($data['id']);
        $res = $this->getModel()->save($data);

        if($res<0){
            $this->returnError(404,null);
        }else{
            $inserted = $this->getModel()->findById($res);

            $this->checkClientId($client_id,$inserted,$value_for_file);

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

/* function getAll(){
     $days=$this->model->getEventsGroupByDay($this->getPaginator());

     $reportDay=array();
     for ($i = 0; $i < count($days); ++$i) {
         $dates=$this->getDates($days[$i]['created']);

         $list=$this->model->getAll($this->filterSale($this->filters($dates),$this->filters($dates),$this->filters($dates)));

         $reportEvents=array();
         $reportItems=array();
         $reportIncomes=array();
         for($j = 0; $i < count($list); ++$j){

             if($list[$j]['TYPE'] == 1){
                 $stock_event = $this->model->findById($list[$j]['stock_id']);
                 $reportEvents[]=array('item' => $stock_event['item'],'type' => $stock_event['type'],'brand' =>$stock_event['brand'],'model' => $stock_event['model'],
                     'stock_in' => $stock_event['stock_in'],'stock_out' => $stock_event['stock_out'],'stock_event_created' => $stock_event['stock_event_created'],
                     'value' => $stock_event['value'], 'payment_method'=> $stock_event['payment_method'], 'detail'=> $stock_event['detail'],'stock_event_id' => $stock_event['stock_event_id'],
                     'client_name' => $stock_event['client_name']);
             }
         }


     }

     $this->returnSuccess(200,$reportDay);
 }*/