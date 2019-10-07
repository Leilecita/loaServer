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