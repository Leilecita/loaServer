<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 09/07/2020
 * Time: 11:57
 */


require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/StockGeneralEventModel.php';
class StockGeneralEventsController extends SecureBaseController
{

    function __construct(){
        parent::__construct();
        $this->model = new StockGeneralEventModel();
    }

    function getGeneralStockEvents(){

        $filters=parent::getFilters();

              if(isset($_GET['type'])) {
                  if ($_GET['type'] != "Todos") {
                      $filters[] = 'type = "' . $_GET['type'] . '"';
                  }
              }
        if(isset($_GET['item'])) {
            if ($_GET['item'] != "Todos") {
                $filters[] = 'item = "' . $_GET['item'] . '"';
            }
        }

        $this->returnSuccess(200, $this->model->getAllGeneralEvents($filters, $this->getPaginator()));
    }


}