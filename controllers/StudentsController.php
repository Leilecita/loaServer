<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 22/12/2020
 * Time: 17:04
 */


require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/StudentModel.php';

class StudentsController extends BaseController
{

    function __construct(){
        parent::__construct();
        $this->model = new StudentModel();
    }

    public function getFilters()
    {

        $filters = parent::getFilters(); // TODO: Change the autogenerated stub
        if(isset($_GET['query']) && !empty($_GET['query'])){
            $filters[] = 'nombre like "%'.$_GET['query'].'%"';
        }

        if(isset($_GET['category'])) {
            if ($_GET['category'] != "todos") {
                $filters[] = 'category = "' . $_GET['category'] . '"';
            }
        }

        return $filters;
    }


    function getStudents(){

        $this->returnSuccess(200, $this->model->findAll($this->getFilters(),$this->getPaginator() ));
    }


}