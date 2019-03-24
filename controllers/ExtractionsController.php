<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 19/12/2018
 * Time: 17:36
 */

require_once 'BaseController.php';
require_once  __DIR__.'/../models/ExtractionModel.php';
require_once  __DIR__.'/../models/BoxModel.php';

class ExtractionsController extends BaseController
{

    private $boxes;
    function __construct(){
        parent::__construct();
        $this->model = new ExtractionModel();
        $this->boxes= new BoxModel();
    }


    function amountExtractions(){
        if(isset($_GET['date']) && isset($_GET['dateTo'])){

            $totalAmount=$this->getModel()->amountByExtractionsDay($_GET['date'],$_GET['dateTo']);
            $this->returnSuccess(200,$totalAmount);
        }
    }

}