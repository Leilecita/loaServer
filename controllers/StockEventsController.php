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
require_once  __DIR__.'/../models/ExtractionModel.php';
require_once  __DIR__.'/../models/ParallelMoneyMovementModel.php';

class StockEventsController extends SecureBaseController
{
    private $products;
    private $items_file;
    private $incomes;
    private $extractions;
    private $parallelMovemens;
    function __construct(){
        parent::__construct();
        $this->model = new StockEventModel();
        $this->products = new ProductModel();
        $this->items_file = new ItemFileModel();
        $this->incomes = new IncomeModel();
        $this->extractions = new ExtractionModel();
        $this->parallelMovemens = new ParallelMoneyMovementModel();
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

   function statisticsFilters(){
       $filters=array();

       if(isset($_GET['model'])){
           if ($_GET['model'] != "Todos") {
               $filters[] = 'model = "' . $_GET['model'] . '"';
           }
       }

       if(isset($_GET['brand'])){
           if ($_GET['brand'] != "Todos") {
               $filters[] = 'brand = "' . $_GET['brand'] . '"';
           }
       }
       if(isset($_GET['type'])) {
           if ($_GET['type'] != "Todos") {
               $filters[] = 'type = "' . $_GET['type'] . '"';
           }
       }
       if(isset($_GET['item'])) {
           if ($_GET['item'] != "Todos") {
               $filters[] = 'item = "' . $_GET['item'] . '"';
           }
       }

       if(isset($_GET['date'])) {
           if ($_GET['date'] != "Todos") {
               $filters[] = 's.created >= "' . $_GET['date'] . '"';
           }
       }

       if(isset($_GET['dateTo'])) {
           if ($_GET['dateTo'] != "Todos") {
               $filters[] = 's.created < "' . $_GET['dateTo'] . '"';
           }
       }

       if( isset($_GET['details']) && $_GET['details'] != ""){
           return $this->filtersDetail($filters);
       }

       if( isset($_GET['detailsToSee']) && $_GET['detailsToSee'] != ""){
           return $this->filtersDetailToSee($filters);
       }

       return $filters;
   }

    function statisticsProductFilters(){
        $filters=array();

        if(isset($_GET['model'])){
            if ($_GET['model'] != "Todos") {
                $filters[] = 'model = "' . $_GET['model'] . '"';
            }
        }

        if(isset($_GET['brand'])){
            if ($_GET['brand'] != "Todos") {
                $filters[] = 'brand = "' . $_GET['brand'] . '"';
            }
        }
        if(isset($_GET['type'])) {
            if ($_GET['type'] != "Todos") {
                $filters[] = 'type = "' . $_GET['type'] . '"';
            }
        }
        if(isset($_GET['item'])) {
            if ($_GET['item'] != "Todos") {
                $filters[] = 'item = "' . $_GET['item'] . '"';
            }
        }

        return $filters;
    }

   function statisticsOutcomesFilers($type){
       $filters=array();

       if(isset($_GET['date'])) {
           if ($_GET['date'] != "Todos") {
               $filters[] = 'created >= "' . $_GET['date'] . '"';
           }
       }

       if(isset($_GET['dateTo'])) {
           if ($_GET['dateTo'] != "Todos") {
               $filters[] = 'created < "' . $_GET['dateTo'] . '"';
           }
       }

       $filters[] = 'type = "' .$type. '"';

       return $filters;
   }

   function getStatisticsSales(){
        $listEvents = $this->model->getAllEvents($this->statisticsFilters(),$this->getPaginator());
        $listReport=$this->getStockEventReport($listEvents);


        $this->returnSuccess(200,$listReport);
    }

    function getDistinctDetails(){

        $this->returnSuccess(200,$this->model->getDistinctsEventsDetail($this->statisticsFilters()));
    }

    function getStatisticsValues(){

        $sumSales = $this->model->sumSales($this->statisticsFilters());
        $sumEntries = $this->model->sumEntries($this->statisticsFilters());
        $sumAmountMoneySales = $this->model->sumAmountMoneySales($this->statisticsFilters());

        $sumStockProduct = $this->model->sumStock($this->statisticsProductFilters());

        $sumLocalExtractions = $this->extractions->amountExtractions($this->statisticsOutcomesFilers('Gasto local'));
        $sumSalarieOutcomes = $this->parallelMovemens->amountMoney($this->statisticsOutcomesFilers('Pago Sueldo'));
        $sumMercaderiaOutcomes = $this->parallelMovemens->amountMoney($this->statisticsOutcomesFilers('Pago mercaderia'));

        $report = array('sum_sales' => $sumSales, 'sum_entries' => $sumEntries,
            'sum_stock_product' => $sumStockProduct,
            'sum_money_sales' => $sumAmountMoneySales,
            'sum_local_extractions' => $sumLocalExtractions['total'],
            'sum_salaries_outcomes' => $sumSalarieOutcomes['total'],
            'sum_mercaderia_outcomes' => $sumMercaderiaOutcomes['total'],
            );

        $this->returnSuccess(200,$report);
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
        $filters[] = '(detail like "%'."salida".'%" OR detail like "%'."Ingreso dev".'%" OR detail like "%'."Suma por error anterior".'%")';
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


    function getAllEntries(){

        if($_GET['groupby'] === "month"){
            $dates=$this->getDatesMonth($_GET['created']);
        }else{
            $dates=$this->getDates($_GET['created']);
        }
        $this->returnSuccess(200,$this->model->getAllEventsSale($this->filterEntrie($this->filters($dates))));
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

            $sumEntries= $this->model->sumEntries($this->filterEntrie($this->filters($dates)));

            $reportDay[]=array('created'=>$days[$i]['created'],'listEntries' => array(),'countEntries' => $sumEntries);
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


    function getSales(){

        if($_GET['groupby'] === "month"){
            $days=$this->model->getEventsGroupByMonth($this->getPaginator());
        }else{
            $days=$this->model->getEventsGroupByDay($this->getPaginator());
        }

       $reportDay=array();
       for ($i = 0; $i < count($days); ++$i) {

           if($_GET['groupby'] === "month"){
               $dates=$this->getDatesMonth($days[$i]['created']);
           }else{
               $dates=$this->getDates($days[$i]['created']);
           }

           $efectAmount=$this->model->amountSaleByDateByMethodPaymentSales($dates['date'],$dates['dateTo'],"efectivo");
           $transfAmount=$this->model->amountSaleByDateByMethodPaymentSales($dates['date'],$dates['dateTo'],"transferencia");
           $mercPagAmount=$this->model->amountSaleByDateByMethodPaymentSales($dates['date'],$dates['dateTo'],"mercado pago");

           $debitoAmount=$this->model->amountSaleByDateByMethodPaymentSales($dates['date'],$dates['dateTo'],"debito");
           $creditAmount=$this->model->amountSaleByDateByMethodPaymentSales($dates['date'],$dates['dateTo'],"tarjeta");

           $efectAmountItemsFileClientSales=$this->items_file->amountByDateEf($dates['date'],$dates['dateTo'],"efectivo");

           $cardAmountItemsFileClientCard=$this->items_file->amountByDateCardDeb($dates['date'],$dates['dateTo'],"efectivo");

           $totalEf=$efectAmount['total']+$efectAmountItemsFileClientSales['total'];

           $totalCard= $debitoAmount['total']+$creditAmount['total']+$cardAmountItemsFileClientCard['total'];

           $countSales= $this->model->sumSales($this->filterSale($this->filters($dates)));

           $reportDay[]=array('created'=>$days[$i]['created'],'countSales' => $countSales, 'efectAmount' => $totalEf, 'cardAmount' => $totalCard ,
               'transfAmount'=>$transfAmount['total'], 'mercPagoAmount' => $mercPagAmount['total'],
               'listStockEventSale' => array(), 'listItems' => array());
       }

       $this->returnSuccess(200,$reportDay);
   }


    function getAllSales(){

        if($_GET['groupby'] === "month"){
            $dates=$this->getDatesMonth($_GET['created']);
        }else{
            $dates=$this->getDates($_GET['created']);
        }
       $this->returnSuccess(200,$this->model->getAllEventsSale($this->filterSale($this->filters($dates))));
   }


    function getAllItemsFile(){

        if($_GET['groupby'] === "month"){
            $dates=$this->getDatesMonth($_GET['created']);
        }else{
            $dates=$this->getDates($_GET['created']);
        }
        $this->returnSuccess(200,$this->items_file->getItemsFileClientEvents($dates['date'],$dates['dateTo']));
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
                'value' => $repot_stock_event['value'], 'payment_method'=> $repot_stock_event['payment_method'], 'detail'=> $repot_stock_event['detail'],'stock_event_id' => $repot_stock_event['stock_event_id'],
                'today_created_client' => $repot_stock_event['today_created_client'], 'client_id' => $repot_stock_event['client_id']);
            $this->returnSuccess(200,$report);
        }else{
            $this->returnError(400, "entity not found");
        }
    }


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
            'product_kind' => $product['item'],'value' =>$value_for_file*(-1),'retired_product' => "true", 'payment_method' =>$inserted['payment_method'],'settled' => "false"
          ,'product_type' => $product['type'],'product_model' => $product['model'] );

        $res=$this->items_file->save($itemFile);
    }

    function post(){
        $data = (array)json_decode(file_get_contents("php://input"));

        $this->updateStockProduct($data['id_product'],$data['stock_in'],$data['stock_out']);

        $client_id=$data['client_id'];
       // unset($data['client_id']);

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

    //cierre de caja
    function getAmountSaleByDate(){

        $dates=$this->getDates($_GET['created']);

        //$totalAmountItemsFileClientSales=$this->items_file->amountByDateEf($date,$dateTo,"efectivo");
        $totalAmountItemsFileClientSales=$this->items_file->amountByDateEf($dates['date'],$dates['dateTo'],"efectivo");

       // $totalAmountIncomes= $this->incomes->amountByDateEf($date,$dateTo,"efectivo");
        $totalAmountIncomes= $this->incomes->amountByDateEf($dates['date'],$dates['dateTo'],"efectivo");

       // $totalAmount=$this->model->amountSaleByDateByMethodPayment($date,$dateTo,"efectivo");
        $totalAmount=$this->model->amountSaleByDateByMethodPaymentSales($dates['date'],$dates['dateTo'],"efectivo");

        $total=array('total' => $totalAmount['total']+$totalAmountItemsFileClientSales['total']+$totalAmountIncomes['total']);

        $this->returnSuccess(200,$total);
    }



    function filterSumSale($dates){
        //nos vamos a guiar si es un pago que se quiere ver reflejado en la caja, sabiendo que tipo de pago utilizó-

        $filters=array();
        $filters[] = 'created >= "'.$dates['date'].'"';
        $filters[] = 'created < "'.$dates['dateTo'].'"';

       // $filters[] = '(detail like "%'."salida".'%" OR detail like "%'."Ingreso dev".'%" OR detail like "%'."Suma por error anterior".'%")';

        $filters[] = '(payment_method like "%'."transferencia".'%" OR payment_method like "%'."mercado pago".'%" OR payment_method like "%'."debito".'%" OR payment_method like "%'."credito".'%")';

        return $filters;
    }

    function getAmountSaleByDateCard(){

        $dates=$this->getDates($_GET['created']);

        //suma todos los que son distinto a efectivo
        $totalAmountItemsFileClientCard=$this->items_file->amountByDateCardDeb($dates['date'],$dates['dateTo'],"efectivo");

        //suma todos los que son distinto a efectivo
        $totalAmountIncomes=$this->incomes->amountByDateCardDeb($dates['date'],$dates['dateTo'],"efectivo");

        //suma todos los que son distinto a efectivo
        //POR EL MOMENTO LO DEJAMOS ASI
       // $totalAmount=$this->model->amountSaleByDateCardDeb($dates['date'],$dates['dateTo'],"efectivo");


        //suma de transfr, credito, debito y mercado pago.
        $totalAmount = $this->model->amountSaleByDateByMethodPaymentOnlySales($this->filterSumSale($dates));

        $total=array('total' => $totalAmount['total']+$totalAmountItemsFileClientCard['total']+$totalAmountIncomes['total']);

        $this->returnSuccess(200,$total);
    }



    //lo usa cierre de caja  --


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

    function filtersDetail($filters){

        $array = explode(";", $_GET['details']);

        foreach ($array as $value)
        {
            error_log($value);

            $filters[] = 'detail != "' .$value. '"';

        }

        return $filters;

    }

    function filtersDetailToSee($filters){

        $array = explode(";", $_GET['detailsToSee']);


       // $filters[] = '(comcli like "%'.$_GET['query'].'%" OR nomcli like "%'.$_GET['query'].'%" OR dircli like "%'.$_GET['query'].'%")';

        $filtersOr = array();

        foreach ($array as $value)
        {

            $filtersOr[] = 'detail = "' .$value. '"';

        }

        $conditions = join(' OR ',$filtersOr);

        error_log($conditions);

      //  '(comcli like "%'.$_GET['query'].'%" OR nomcli like "%'.$_GET['query'].'%" OR dircli like "%'.$_GET['query'].'%")';

        $filters[] = "(".$conditions.")";

        return $filters;
    }
}

