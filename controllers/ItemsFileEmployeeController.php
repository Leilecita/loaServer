<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 16/12/2018
 * Time: 14:20
 */
require_once 'BaseController.php';
require_once  __DIR__.'/../models/ItemFileEmployeeModel.php';
class ItemsFileEmployeeController extends BaseController
{

    function __construct(){
        parent::__construct();
        $this->model = new ItemFileEmployeeModel();
    }

    function listHours()
    {
        if (isset($_GET['employee_id'])) {
            $this->returnSuccess(200, $this->model->findAllByEmployeeId(array('employee_id = "' . $_GET['employee_id'] . '"'),$this->getPaginator()));
        } else {
            $this->returnError(404, "ENTITY NOT FOUND");
        }
    }

    function amountHoursByMonth(){
        if(isset($_GET['since']) && isset($_GET['to'])){

            $totalAmount=$this->getModel()->amountHoursByMonth($_GET['since'],$_GET['to'],$_GET['employee_id']);
            $this->returnSuccess(200,$totalAmount);
        }
    }

    public function get()
    {
        if(isset($_GET['method'])){
            $this->method();
        }else{
            parent::get();
        }
    }
}