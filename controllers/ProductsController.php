<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 05/04/2019
 * Time: 12:21
 */

require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/ProductModel.php';

class ProductsController extends SecureBaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new ProductModel();
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
        return $filters;
    }

    public function deleteProduct()
    {
        $this->model->update($_GET['id'],array('deleted' => 'true'));

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

        $res=$this->getModel()->findAll($filters2, $this->getPaginator());
        if(count($res)>0){

            $resp=array('res' => "existe");
            $this->returnSuccess(200,$resp);

        }else{

            $product=array('item' => $_GET['item'] ,'type' => $_GET['type'],'brand' => $_GET['brand'],'model' => $_GET['model'] ,'stock' => 0, 'deleted' => "false");

            $res = $this->getModel()->save($product);
            if($res<0){

                $resp=array('res' => "error al crear el prod");
                $this->returnError(400,$resp);
            }else{
                $resp=array('res' => "creado");
                $this->returnSuccess(200,$resp);
            }
        }
    }

    function getSpinners(){

        $listItems=$this->getModel()->getSpinner($this->assignFilter(),"item");
        $listBrands=$this->getModel()->getSpinner($this->assignFilter(),"brand");
        $listType=$this->getModel()->getSpinner($this->assignFilter(),"type");
        $listModel=$this->getModel()->getSpinner($this->assignFilter(),"model");

        $resp=array("items" => $listItems,"brands" => $listBrands,"types" => $listType, "models" => $listModel);

        $this->returnSuccess(200,$resp);

    }

   /* function getProducts()
    {
        $filters = array();

        if ($_GET['brand'] == "Marca" && $_GET['type'] == "Articulo") {
            $this->returnSuccess(200, $this->getModel()->findAll($this->getFilters(), $this->getPaginator()));
        }

        if ($_GET['brand'] != "Marca" && $_GET['type'] == "Articulo") {
            if (isset($_GET['brand'])) {
                $filters[] = 'brand = "' . $_GET['brand'] . '"';
            }
            $this->returnSuccess(200, $this->getModel()->findAll($filters, $this->getPaginator()));
        }

        if ($_GET['brand'] == "Marca" && $_GET['type'] != "Articulo") {
            if (isset($_GET['type'])) {
                $filters[] = 'type = "' . $_GET['type'] . '"';
            }
            $this->returnSuccess(200, $this->getModel()->findAll($filters, $this->getPaginator()));
        }

        if ($_GET['brand'] != "Marca" && $_GET['type'] != "Articulo") {
            if (isset($_GET['type'])) {
                $filters[] = 'type = "' . $_GET['type'] . '"';
            }
            if (isset($_GET['brand'])) {
                $filters[] = 'brand = "' . $_GET['brand'] . '"';
            }
            $this->returnSuccess(200, $this->getModel()->findAll($filters, $this->getPaginator()));
        }
    }*/
}