<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 16/12/2018
 * Time: 14:20
 */
require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/ItemFileEmployeeModel.php';
class ItemsFileEmployeeController extends SecureBaseController
{

    function __construct(){
        parent::__construct();
        $this->model = new ItemFileEmployeeModel();
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

        $filters[] = 'created >= "'.$dates['date'].'"';
        $filters[] = 'created < "'.$dates['dateTo'].'"';

        return $filters;
    }

    function filtersTypeWithEmployeeId($dates,$employee_id){

        $filters=array();

        $filters[] = 'created >= "'.$dates['date'].'"';
        $filters[] = 'created < "'.$dates['dateTo'].'"';
        $filters[] = 'employee_id = "'.$employee_id.'"';

        return $filters;

    }

    function getHoursByMonthEmployee(){

        $listMonths=$this->model->getMonthsGroup($this->getPaginator(),$_GET['employee_id']);

        $report=array();
        for ($k = 0; $k < count($listMonths); ++$k) {

            $dates=$this->getDatesMonth($listMonths[$k]['created']);

           // $amountHoursByMonth=$this->model->amountHoursByMonthItem($this->filtersType($dates));
            $amountHoursByMonth=$this->model->amountHoursByMonthItem($this->filtersTypeWithEmployeeId($dates,$_GET['employee_id']));

            //$listItemsFile= $this->model->listAll($this->filtersType($dates));
            $listItemsFile= $this->model->listAll($this->filtersTypeWithEmployeeId($dates,$_GET['employee_id']));

            $report[]=array('created'=>$listMonths[$k]['created'],'amountMonth' => $amountHoursByMonth['total'],'listItemsFile' => $listItemsFile);
        }

        $this->returnSuccess(200,$report);

    }




    function listHours()
    {
        if (isset($_GET['employee_id'])) {
           // $this->returnSuccess(200, $this->model->findAllByEmployeeId(array('employee_id = "' . $_GET['employee_id'] . '"'),$this->getPaginator()));
            $this->returnSuccess(200, $this->model->findAllByEmployeeId($this->getFilters(),$this->getPaginator()));
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



}