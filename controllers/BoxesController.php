<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 11/03/2019
 * Time: 16:20
 */


require_once 'BaseController.php';
require_once  __DIR__.'/../models/BoxModel.php';
require_once  __DIR__.'/../models/ExtractionModel.php';

class BoxesController extends BaseController
{
    private $extractions;

    function __construct(){
        parent::__construct();
        $this->model = new BoxModel();
        $this->extractions = new ExtractionModel();
    }

    function updateBoxes(){
        $boxes=$this->getModel()->findAll($this->getFilters(),$this->getPaginator());
        for ($i = 0; $i < count($boxes); ++$i) {

            $created=$boxes[$i]['created'];

            $parts = explode(" ", $created);
            $date=$parts[0]." 00:00:00";

            $next_date = date('Y-m-d', strtotime( $parts[0].' +1 day'));


            $dateTo=$next_date." 00:00:00";

            $totalAmount=$this->extractions->amountByExtractionsDay($date,$dateTo);

            $this->model->update($boxes[$i]['id'],array('deposit' => $totalAmount));

        }

    }

    function getBoxes(){

        $boxes=$this->getModel()->findAll($this->getFilters(),$this->getPaginator());
        for ($i = 0; $i < count($boxes); ++$i) {

            $created=$boxes[$i]['created'];

            $parts = explode(" ", $created);
            $date=$parts[0]." 00:00:00";

            $next_date = date('Y-m-d', strtotime( $parts[0].' +1 day'));


            $dateTo=$next_date." 00:00:00";

            $totalAmount=$this->extractions->amountByExtractionsDay($date,$dateTo);

            $this->model->update($boxes[$i]['id'],array('deposit' => $totalAmount));

        }

        $this->returnSuccess(200,$boxes);
    }


}