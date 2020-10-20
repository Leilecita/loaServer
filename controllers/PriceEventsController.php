<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 12/10/2020
 * Time: 13:11
 */


require_once 'SecureBaseController.php';
require_once __DIR__ . '/../models/PriceEventModel.php';
require_once  __DIR__.'/../models/UserModel.php';

class PriceEventsController extends SecureBaseController
{

    private $users;

    function __construct(){
        parent::__construct();
        $this->model = new PriceEventModel();
        $this->users = new UserModel();
    }

    function getPriceEvents(){

        $list = $this->model->getAllPriceEvents($this->getFilters(), $this->getPaginator());

        $report = array();
        for ($i = 0; $i < count($list); ++$i) {

            $user = $this->users->findById($list[$i]['user_id']);

            $report[]=array('previous_price' => $list[$i]['previous_price'],
                'actual_price' => $list[$i]['actual_price'],
                'user_name' => $user['name'],
                'item' => $list[$i]['item'],
                'brand' => $list[$i]['brand'],
                'type' => $list[$i]['type'],
                'model' => $list[$i]['model'],
                'percentage' => $list[$i]['percentage'],
                'created' => $list[$i]['price_event_created']);
        }

        $this->returnSuccess(200,$report);
    }
}




