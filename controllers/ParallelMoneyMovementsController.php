<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 18/06/2020
 * Time: 15:42
 */
require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/ParallelMoneyMovementModel.php';

class ParallelMoneyMovementsController extends SecureBaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new ParallelMoneyMovementModel();
    }

    function getDatesMonth($data){

        $partsYearMonthDay=explode("-", $data);
        $partsYearMonthDay[2]=01;

        $date= $partsYearMonthDay[0]."-".$partsYearMonthDay[1]."-".$partsYearMonthDay[2]." 00:00:00";

        $next_month = date('Y-m-d', strtotime( $date.' +1 month'));
        $dateTo=$next_month." 00:00:00";
        $result=array('date' => $date, 'dateTo' => $dateTo);

        return $result;
    }


    function getDates($data){

        $parts = explode(" ", $data);
        $date=$parts[0]." 00:00:00";
        $next_date = date('Y-m-d', strtotime( $parts[0].' +1 day'));
        $dateTo=$next_date." 00:00:00";
        $result=array('date' => $date, 'dateTo' => $dateTo);
        return $result;
    }


    function filtersType($dates){

        $filters=array();

        if(isset($_GET['type'])) {
            if ($_GET['type'] != "Todo") {
                $filters[] = 'type = "' . $_GET['type'] . '"';
            }
        }

        if(isset($_GET['billed'])) {
            if ($_GET['billed'] != "Todo") {
                $filters[] = 'billed = "' . $_GET['billed'] . '"';
            }
        }

        $filters[] = 'created >= "'.$dates['date'].'"';
        $filters[] = 'created < "'.$dates['dateTo'].'"';

        return $filters;
    }


    function getMoneyMovements(){

        if($_GET['groupby'] === "month"){
            $listDays=$this->model->getMonthsGroup($this->getPaginator());
        }else{
            $listDays=$this->model->getDaysGroup($this->getPaginator());
        }

        $report=array();
        for ($k = 0; $k < count($listDays); ++$k) {

            if($_GET['groupby'] === "month"){
                $dates=$this->getDatesMonth($listDays[$k]['created']);
            }else{
                $dates=$this->getDates($listDays[$k]['created']);
            }

            $amountByDay=$this->model->amountByDay($this->filtersType($dates));

            $listExtrByDay= $this->model->listAll($this->filtersType($dates));

            $report[]=array('created'=>$listDays[$k]['created'],'amountDay' => $amountByDay['total'],'listMoneyMovements' => $listExtrByDay);
        }
        $this->returnSuccess(200,$report);
    }

}