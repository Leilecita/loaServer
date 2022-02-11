<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 20/11/2020
 * Time: 15:55
 */

require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/ParallelBillingModel.php';

class ParallelBillingsController extends SecureBaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new ParallelBillingModel();
    }

    function getDistinctTypes(){
        $this->returnSuccess(200, $this->model->getDistinctTypes());
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

        $filters[] = 'created >= "'.$dates['date'].'"';
        $filters[] = 'created < "'.$dates['dateTo'].'"';

        return $filters;
    }

    function getParallelBillingsMonth(){

        $listMonths=$this->model->getMonthsGroup($this->getPaginator());

        $report=array();
        for ($k = 0; $k < count($listMonths); ++$k) {

            $dates=$this->getDatesMonth($listMonths[$k]['created']);

            $amountByDay=$this->model->amountMoney($this->filtersType($dates));

            $listExtrByDay= $this->model->listAll($this->filtersType($dates));

            $report[]=array('created'=>$listMonths[$k]['created'],'amount_month' => $amountByDay,'list' => $listExtrByDay);
        }
        $this->returnSuccess(200,$report);
    }

}