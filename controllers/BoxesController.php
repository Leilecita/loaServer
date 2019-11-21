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


    function getBoxes(){
        //$this->beforeMethod();

        $boxes=$this->getModel()->findAll($this->getFilters(),$this->getPaginator());
       /* for ($i = 0; $i < count($boxes); ++$i) {

            $created=$boxes[$i]['created'];

            $parts = explode(" ", $created);
            $date=$parts[0]." 00:00:00";

            $next_date = date('Y-m-d', strtotime( $parts[0].' +1 day'));


            $dateTo=$next_date." 00:00:00";

            $totalAmount=$this->extractions->amountByExtractionsDay($date,$dateTo);

            $this->model->update($boxes[$i]['id'],array('deposit' => $totalAmount));

        }
*/
        $this->returnSuccess(200,$boxes);
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

        $boxesByMonth=$this->getModel()->getAmountBoxByMonth($this->getFilters(),$this->getPaginator());
        for ($i = 0; $i < count($boxesByMonth); ++$i) {


        }


        $this->returnSuccess(200, $this->getModel()->getAmountBoxByMonth($this->getFilters(),$this->getPaginator()));


    }


}