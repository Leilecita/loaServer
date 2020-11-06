<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 05/04/2019
 * Time: 12:21
 */

require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/ProductModel.php';
require_once  __DIR__.'/../models/BrandModel.php';
require_once  __DIR__.'/../models/TypeModel.php';
require_once __DIR__ . '/../models/PriceEventModel.php';
require_once __DIR__ . '/../models/StockEventModel.php';

class ProductsController extends SecureBaseController
{
    private $brands;
    private $types;
    private $priceEvents;
    private $stockEvents;

    function __construct(){
        parent::__construct();
        $this->model = new ProductModel();
        $this->brands = new BrandModel();
        $this->types = new TypeModel();
        $this->priceEvents = new PriceEventModel();
        $this->stockEvents = new StockEventModel();
    }

    function filterBrand($filters){
        $filters[]='p.brand_id = b.id' ;
        return $filters;
    }

    function filterType($filters){
        $filters[]='p.type_id = t.id' ;
        return $filters;
    }

    function assignFilter(){
        $filters = array();

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
        if(isset($_GET['deleted'])) {
            $filters[] = 'deleted = "' . $_GET['deleted'] . '"';
        }

        if(isset($_GET['query']) && !empty($_GET['query'])){
            $filters[] = '(brand like "%'.$_GET['query'].'%" OR model like "%'.$_GET['query'].'%" OR type like "%'.$_GET['query'].'%")';
        }

        return $filters;
    }

    public function deleteProduct()
    {
        $this->model->update($_GET['id'],array('deleted' => 'true'));

        $product = $this->model->findById($_GET['id']);

        $this->createStockEvent($product,"Eliminado", $_GET['observation']);

        $resp=array('brand'=>"ok");
        $this->returnSuccess(200,$resp);
    }


    function spinner(){
        if($_GET['tt']){
            $this->returnSuccess(200,$this->getModel()->getSpinner($this->assignFilter(),$_GET['tt']));
        }
    }

    function getDeletedProducts(){
        $this->returnSuccess(200, $this->getModel()->findAll($this->assignFilter(), $this->getPaginator()));
    }

    function getProducts2(){
        $this->returnSuccess(200, $this->getModel()->findAll($this->assignFilter(), $this->getPaginator()));
    }

    function getProductsWithPreviousPrices(){

        $list = $this->getModel()->findAll($this->assignFilter(), $this->getPaginator());

        $reportProdu = array();

        for ($j = 0; $j < count($list); ++$j) {

            $event_prices = $this->priceEvents->findAllEvents(array('product_id = "'.$list[$j]['id'].'"' ));

            $actual_price = 0;
            $previous_price = 0;
            if(!empty($event_prices)){
                $actual_price = $event_prices[0]['actual_price'];
                $previous_price =  $event_prices[0]['previous_price'];
            }

            $reportProdu[] = array('product_id' => $list[$j]['id'], 'item' => $list[$j]['item'], 'type' => $list[$j]['type'], 'brand' => $list[$j]['brand'],
                'model' => $list[$j]['model'], 'deleted' => $list[$j]['deleted'], 'stock' => $list[$j]['stock'], 'price' => $list[$j]['price'],
                'previous_price' => $previous_price, 'actual_price' => $actual_price );

        }

        $this->returnSuccess(200,$reportProdu);
    }

    function getProductsByItemType(){
        $this->returnSuccess(200, $this->model->findAllProducts($this->assignFilter()));
    }

    function sumAllStock(){
        $this->returnSuccess(200, $this->getModel()->sumAll($this->assignFilter(), $this->getPaginator()));
    }

    function checkExistProduct(){

        $filters2=array();
        $filters2[] = 'brand = "' . $_GET['brand'] . '"';
        $filters2[] = 'item = "' . $_GET['item'] . '"';
        $filters2[] = 'type = "' . $_GET['type'] . '"';
        $filters2[] = 'model = "' . $_GET['model'] . '"';
        $filters2[] = 'deleted = "false"';

        $detailEvent = $_GET['detail'];
        $productPrice = $_GET['price'];

        $res=$this->getModel()->findAll($filters2, $this->getPaginator());
        if(count($res)>0){

            $resp=array('res' => "existe");
            $this->returnSuccess(200,$resp);

        }else{

            $product=array('item' => $_GET['item'] ,'type' => $_GET['type'],'brand' => $_GET['brand'],'model' => $_GET['model'] ,'stock' => $_GET['stock'],
                'deleted' => "false", 'price' => $productPrice);

            $res = $this->getModel()->save($product);
            if($res<0){

                $resp=array('res' => "error al crear el prod");
                $this->returnError(400,$resp);
            }else{

                $createdProduct=$this->model->findById($res);
                if($createdProduct){
                    $this->checkExistTypeAndBrand($createdProduct);
                }

                $resp=array('res' => "creado");

                $this->createStockEvent($createdProduct,$detailEvent,"");

                if($createdProduct['price'] > 0){
                    $this->generatePriceEvent($createdProduct['id'], 0, $createdProduct['price'],0);
                }

                $this->returnSuccess(200,$resp);
            }

        }
    }

    function createStockEvent($product, $detail, $observation){

        $stockEvent= array('id_product' => $product['id'], 'stock_in' => $product['stock'], 'stock_out' => 0,'stock_ant' => 0,'ideal_stock' => 0 , 'detail' => $detail, 'value' => 0,
            'payment_method' => "", 'client_id' => -1,'today_created_client' => "false", 'observation' => $observation);

        $this->stockEvents->save($stockEvent);

    }

    function getSpinners(){

        $listItems=$this->getModel()->getSpinner($this->assignFilter(),"item");

        $listBrands=$this->model->getSpinnerBrands($this->filterBrand($this->assignFilter()));

        $listType=$this->model->getSpinnerTypes($this->filterType($this->assignFilter()));

        $listModel=$this->model->getSpinnerModel($this->assignFilter(),"model");

        $resp=array("items" => $listItems,"brands" => $listBrands,"types" => $listType, "models" => $listModel);

        $this->returnSuccess(200,$resp);
    }


    function getProductsByItem(){

        $this->returnSuccess(200,$this->model->getProductsByDistintctItem());
    }

    function getProductsByType(){

        $this->returnSuccess(200,$this->model->getProductsByDistintctType($this->filterType($this->assignFilter())));
    }

    function checkExistTypeAndBrand($data){

        $brand=$this->brands->find(array('name = "'.$data['brand'].'"' ));

        if($brand){
            $this->model->update($data['id'],array('brand_id'=> $brand['id']));
        }else{

            $colors=$this->colors();
            $newBrand= array('name' => $data['brand'],'color' => $colors[ rand(0,23)]);
            $res=$this->brands->save($newBrand);

            if($res<0){
                error_log("error al crear la marca");
            }else{
                $this->model->update($data['id'],array('brand_id'=> $res));
            }
        }

        $type= $this->types->find(array('name = "'.$data['type'].'"' ));

        if($type){
            $this->model->update($data['id'],array('type_id'=> $type['id']));
        }else{

            $colors=$this->colors();

            $newType= array('name' => $data['type'],'color' => $colors[ rand(0,27)]);
            $res=$this->types->save($newType);
            if($res<0){
                error_log("error al crear el tipo de producto");
            }else{
                $this->model->update($data['id'],array('type_id'=> $res));
            }
        }
    }

    //·················· crear marcas con su color asignado

    //primero creamos las marcas con su color random para cada producto.
    //luego actualizamos el id de las marcas al producto

    function createBrand(){

        $products_by_brands=$this->model->getProductByBrands();
        var_dump(count($products_by_brands));
        for ($j = 0; $j < count($products_by_brands); ++$j) {

            $colors=$this->colors();

            $newBrand= array('name' => $products_by_brands[$j]['brand'],'color' => $colors[ rand(0,23)]);
            $res=$this->brands->save($newBrand);
            if($res<0){
                var_dump("error");
            }else{
                var_dump("correct");
            }
        }
    }

    function loadIdBrandToProduct(){
        $products_by_brands=$this->model->findAllProducts();
        for ($j = 0; $j < count($products_by_brands); ++$j) {

            var_dump($products_by_brands[$j]['brand']);

            $brand=$this->brands->find(array('name = "'.$products_by_brands[$j]['brand'].'"' ));
            if($brand){
                $this->model->update($products_by_brands[$j]['id'],array('brand_id' => $brand['id']));
            }
        }
    }

    function createType(){
        $products_by_types=$this->model->getProductByType();

        for ($j = 0; $j < count($products_by_types); ++$j) {


            $colors=$this->colors();

            $newType= array('name' => $products_by_types[$j]['type'],'color' => $colors[ rand(0,27)]);
            $res=$this->types->save($newType);

            if($res<0){
                var_dump("error");
            }else{
                var_dump("correct");
            }
        }

    }
    function loadIdTypeToProduct(){
        $products=$this->model->findAllProducts();
        for ($j = 0; $j < count($products); ++$j) {

            var_dump($products[$j]['type']);

            $type=$this->types->find(array('name = "'.$products[$j]['type'].'"' ));
            if($type){
                $this->model->update($products[$j]['id'],array('type_id' => $type['id']));
            }
        }
    }

    function put()
    {
        $data = (array) json_decode(file_get_contents("php://input"));

        $this->logPriceEvent($data);

        parent::put(); // TODO: Change the autogenerated stub
    }

    function logPriceEvent($data){

        $previous_data = $this->model->findById($data['id']);

        if($data['price'] != $previous_data['price']){

            $this->generatePriceEvent($data['id'], $previous_data['price'], $data['price'],0);
        }
    }

    function generatePriceEvent($product_id, $previous_price, $actual_price, $percentage){
        $user = $this->getUser();

        if($user != null){
            $user_id = $user['id'];
        }else{
            $user_id = -1;
        }

        $date = new DateTime("now", new DateTimeZone('America/Argentina/Buenos_Aires') );
        $created = $date->format('Y-m-d H:i:s');

        $per =0;
        if($percentage != 0){
            $per = $percentage;
        }

        $price_event = array('user_id' => $user_id ,'product_id' => $product_id, 'previous_price' => $previous_price, 'actual_price' => $actual_price,
            'created' => $created, 'percentage' => $per);

        $this->priceEvents->save($price_event);

    }


    function redondear_a_10($valor) {

        //VER FUNCION CEIL PHP

        // Convertimos $valor a entero
        $valor = intval($valor);

        // Redondeamos al múltiplo de 10 más cercano
        $n = round($valor, -1);

        // Si el resultado $n es menor, quiere decir que redondeo hacia abajo
        // por lo tanto sumamos 10. Si no, lo devolvemos así.
        return $n < $valor ? $n + 10 : $n;
    }

    function roundUp($final_price){

        $div = explode(".",$final_price);

        $integer_part = $div[0];

        error_log('redondear a 10 '. $this->redondear_a_10($integer_part));

        return $this->redondear_a_10($integer_part);
    }


    function updatePrices(){

        $selected_products = $_GET['selected_products'];
        $percentege = $_GET['number'];

        $array = explode(";", $selected_products);

        foreach ($array as $value)
        {
            error_log(number_format($value));

            $product = $this->model->findById((int)$value);

            error_log(gettype($product['id']).' '.$product['item'].' '.$product['brand']);

            $product_percentege = $product['price']*$percentege/100;
            $final_price = $product['price'] + $product_percentege;

            $round_up_final_price = $this->roundUp($final_price);

            error_log('precio '.$product['price'].' nuevo precio '.$final_price.'rounded up '.$round_up_final_price);

            $this->generatePriceEvent($product['id'], $product['price'], $round_up_final_price, $percentege);

            $this->model->update($product['id'], array('price' => $round_up_final_price));

        }

        $resp = array('res' => "ok");

        $this->returnSuccess(200,$resp);
    }


    function colors(){

        $color=array();
        $color[]=array('#E57373');
        $color[]=array('#4DD0E1');

        $color[]=array('#64B5F6');
        $color[]=array('#80CBC4');
        $color[]=array('#80DEEA');

        $color[]=array('#D4E157');
        $color[]=array('#FF8A65');
        $color[]=array('#E57373');
        $color[]=array('#FFB74D');
        $color[]=array('#F06292');
        $color[]=array('#4FC3F7');
        $color[]=array('#9575CD');

        $color[]=array('#90A4AE');
        $color[]=array('#FFD54F');
        $color[]=array('#F9A825');
        $color[]=array('#CE93D8');
        $color[]=array('#FF8A65');
        $color[]=array('#90CAF9');
        $color[]=array('#4DB6AC');

        $color[]=array('#64B5F6');
        $color[]=array('#81C784');
        $color[]=array('#FF8A65');
        $color[]=array('#9FA8DA');
        $color[]=array('#B39DDB');
        $color[]=array('#4FC3F7');
        $color[]=array('#4DB6AC');
        $color[]=array('#BA68C8');
        $color[]=array('#EF9A9A');

        return $color;
    }

}