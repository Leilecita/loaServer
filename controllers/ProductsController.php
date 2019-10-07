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


    function getSpinners(){
        $listItems=$this->getModel()->getSpinner($this->assignFilter(),"item");
        $listBrands=$this->getModel()->getSpinner($this->assignFilter(),"brand");
        $listType=$this->getModel()->getSpinner($this->assignFilter(),"type");

        $resp=array("items" => $listItems,"brands" => $listBrands,"types" => $listType);

        $this->returnSuccess(200,$resp);

    }

    function getProducts()
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
    }
    }