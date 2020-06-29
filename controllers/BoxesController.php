<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 11/03/2019
 * Time: 16:20
 */

require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/BoxModel.php';
require_once  __DIR__.'/../models/ExtractionModel.php';

class BoxesController extends SecureBaseController
{
    private $extractions;

    function __construct(){
        parent::__construct();
        $this->model = new BoxModel();
        $this->extractions = new ExtractionModel();
    }

    function getLastBox(){

        $totalAmount=array('total' => 0.0);
        if(isset($_GET['date']) && isset($_GET['dateTo'])){
            $totalAmount=$this->extractions->amountByExtractionsDay($_GET['date'],$_GET['dateTo']);
        }

        $boxes=$this->model->findAllBoxes($this->getFilters());
        $lastBox=$boxes[0];

        $resp=array('lastBox' => $lastBox, 'amountExtractions' => $totalAmount['total']);
        $this->returnSuccess(200,$resp);
    }



    function getBoxes(){
        //$this->beforeMethod();

        $boxes=$this->getModel()->findAll($this->getFilters(),$this->getPaginator());

        $this->returnSuccess(200,$boxes);
    }

    function getBoxesByPeriod(){

        $listBoxes=$this->model->findAll($this->getFilters(),$this->getPaginator());
        $this->returnSuccess(200,$listBoxes);

    }

    function sumBoxesByPeriod(){

        $ctdo=$this->model->amountByColumnBox($_GET['since'],$_GET['to'],"counted_sale");
        $card=$this->model->amountByColumnBox($_GET['since'],$_GET['to'],"credit_card");
        $tot_box=$this->model->amountByColumnBox($_GET['since'],$_GET['to'],"total_box");
        $rest_box=$this->model->amountByColumnBox($_GET['since'],$_GET['to'],"rest_box");
        $depo=$this->model->amountByColumnBox($_GET['since'],$_GET['to'],"deposit");

        $result=array('tot_ctdo' => $ctdo['total'], 'tot_card' => $card['total'], 'tot_box' => $tot_box['total'],
            'tot_rest_box' => $rest_box['total'],'tot_dep' =>$depo['total']);

        $this->returnSuccess(200,$result);

    }

    function getAmountExtractions($data){
        $created=$data['created'];

        $parts = explode(" ", $created);
        $date=$parts[0]." 00:00:00";
        $next_date = date('Y-m-d', strtotime( $parts[0].' +1 day'));
        $dateTo=$next_date." 00:00:00";
        $filters= array();
        $filters[] = 'created >= "'.$date.'"';
        $filters[] = 'created < "'.$dateTo.'"';

        $totalAmount=$this->extractions->amountByExtractionsDay($date,$dateTo);

        $this->getModel()->update($data['id'],array('deposit'=> $totalAmount));
    }

    function post(){
        $data = (array)json_decode(file_get_contents("php://input"));
        unset($data['id']);
        $res = $this->getModel()->save($data);
        if($res<0){
            $this->returnError(404,null);
        }else{
            $inserted = $this->getModel()->findById($res);
            $this->getAmountExtractions($inserted);
            $this->returnSuccess(201,$inserted);
        }
    }

    function getBoxesByMonth(){

        $boxesByMonth=$this->model->getAmountBoxByMonth($this->getFilters(),$this->getPaginator());

        $list= array();
        for ($i = 0; $i < count($boxesByMonth); ++$i) {

            $listBoxes= $this->model->getBoxesByMonthYear($boxesByMonth[$i]['m'],$boxesByMonth[$i]['y']);
            $list[]=array('y' => $boxesByMonth[$i]['y'], 'm' =>$boxesByMonth[$i]['m'], 'card' => $boxesByMonth[$i]['card'],'sale'=> $boxesByMonth[$i]['sale'],'dep' => $boxesByMonth[$i]['dep'],
            'listBoxesByMonth' => $listBoxes);
        }

        $this->returnSuccess(200, $list);
    }

    function getListBoxesByMonth(){

        $this->returnSuccess(200, $this->getModel()->getBoxesByMonthYear("11","2019"));
    }


}