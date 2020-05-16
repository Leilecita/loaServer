<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 22/11/2019
 * Time: 14:13
 */

require_once 'SecureBaseController.php';
require_once __DIR__ . '/../models/IncomeModel.php';

class IncomesController extends SecureBaseController {

    function __construct() {
        parent::__construct();
        $this->model = new IncomeModel();
    }


    function getFiltersIncome(){
        $filters= array();

        if(isset($_GET['since'])){

            $created=$_GET['since'];

            $parts = explode(" ", $created);
            $date=$parts[0]." 00:00:00";
            $filters[] = 'created >= "'.$date.'"';
        }
        if(isset($_GET['to'])){

            $created=$_GET['to'];
            $parts = explode(" ", $created);
            $date=$parts[0]." 00:00:00";

            $filters[] = 'created < "'.$date.'"';
        }

        return $filters;
    }

    function getIncomes(){



        $res=$this->model->findAllIncomes($this->getFiltersIncome());

        $this->returnSuccess(200,$res);
    }
}