<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 05/04/2019
 * Time: 12:21
 */

require_once 'BaseController.php';
require_once  __DIR__.'/../models/ProductModel.php';

class ProductsController extends BaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new ProductModel();
    }

    function spinner(){

        if($_GET['tt']){
            $filters= array();
            $this->returnSuccess(200,$this->getModel()->getSpinner($filters,$_GET['tt']));

        }

    }



    function getProducts(){
        $filters= array();

        if($_GET['brand'] == "Marca" && $_GET['type']=="Articulo"){

            $this->returnSuccess(200,$this->getModel()->findAll($this->getFilters(),$this->getPaginator()));
        }

        if($_GET['brand'] != "Marca" && $_GET['type']=="Articulo"){
            if(isset($_GET['brand'])){
                $filters[] = 'brand = "'.$_GET['brand'].'"';
            }
            $this->returnSuccess(200,$this->getModel()->findAll($filters,$this->getPaginator()));
        }

        if($_GET['brand'] == "Marca" && $_GET['type']!="Articulo"){
            if(isset($_GET['type'])){
                $filters[] = 'type = "'.$_GET['type'].'"';
            }
            $this->returnSuccess(200,$this->getModel()->findAll($filters,$this->getPaginator()));
        }

        if($_GET['brand'] != "Marca" && $_GET['type']!="Articulo"){
            if(isset($_GET['type'])){
                $filters[] = 'type = "'.$_GET['type'].'"';
            }
            if(isset($_GET['brand'])){
                $filters[] = 'brand = "'.$_GET['brand'].'"';
            }
            $this->returnSuccess(200,$this->getModel()->findAll($filters,$this->getPaginator()));
        }


    }


}