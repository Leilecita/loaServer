<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 19/04/2021
 * Time: 20:21
 */

require_once 'SecureBaseController.php';
require_once __DIR__ . '/../models/BuyBillingModel.php';


class BuyBillingsController extends SecureBaseController
{


    function __construct(){
        parent::__construct();

        $this->model = new BuyBillingModel();
    }



    function getBuyBilling(){
        $this->returnSuccess(200,$this->model->findAll($this->getFilters(),$this->getPaginator()));
    }


    function filters(){
        $filters=$this->getFilters();


        if(isset($_GET['date'])) {
            if ($_GET['date'] != "Todos") {
                $filters[] = 'billing_date >= "' . $_GET['date'] . '"';
            }
        }

        if(isset($_GET['dateTo'])) {
            if ($_GET['dateTo'] != "Todos") {
                $filters[] = 'billing_date < "' . $_GET['dateTo'] . '"';
            }
        }


        return $filters;
    }

    function getReportBuyBilling(){


        $list = $this->model->findAllBillings($this->filters(),$this->getPaginator());

        $tot_art = $this->model->sumCantArt($this->filters());
        $tot_amount = $this->model->sumTotAmount($this->filters());

        $report = array('list' => $list, 'tot_art' => $tot_art, 'tot_amount' => $tot_amount);

        $this->returnSuccess(200,$report);
    }
}