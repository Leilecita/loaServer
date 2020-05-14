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


class ProductsController extends SecureBaseController
{
    private $brands;
    private $types;

    function __construct(){
        parent::__construct();
        $this->model = new ProductModel();
        $this->brands = new BrandModel();
        $this->types = new TypeModel();
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

                $createdProduct=$this->model->findById($res);
                if($createdProduct){
                    $this->checkExistTypeAndBrand($createdProduct);
                }

                $resp=array('res' => "creado");
                $this->returnSuccess(200,$resp);
            }
        }
    }

    function getSpinners(){

        $listItems=$this->getModel()->getSpinner($this->assignFilter(),"item");

        //$listBrands=$this->getModel()->getSpinner($this->assignFilter(),"brand");
        $listBrands=$this->model->getSpinnerBrands($this->filterBrand($this->assignFilter()));

        //$listType=$this->getModel()->getSpinner($this->assignFilter(),"type");
        $listType=$this->model->getSpinnerTypes($this->filterType($this->assignFilter()));


        $listModel=$this->model->getSpinnerModel($this->assignFilter(),"model");

        $resp=array("items" => $listItems,"brands" => $listBrands,"types" => $listType, "models" => $listModel);

        $this->returnSuccess(200,$resp);

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