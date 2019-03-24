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

    function put(){
       /* $data = (array) json_decode(file_get_contents("php://input"));

        $created=$data['created'];

        $parts = explode(" ", $created);
        $date=$parts[0]." 00:00:00";

        $next_date = date('Y-m-d', strtotime( $parts[0].' +1 day'));


        $dateTo=$next_date." 00:00:00";

        $totalAmount=$this->model->amountByExtractionsDay($date,$dateTo);

        $this->model->update($boxes[$i]['id'],array('deposit' => $totalAmount));



        if($this->users->findById($data["user_id"])){

            parent::put();
            $this->updateDebtUser($data);
        }else{
            $this->returnError(404,'Usuario no existe');
        }*/

        parent::put();
        $this->boxes->updateBoxes();
    }





    function amountExtractions(){
        if(isset($_GET['date']) && isset($_GET['dateTo'])){

            $totalAmount=$this->getModel()->amountByExtractionsDay($_GET['date'],$_GET['dateTo']);
            $this->returnSuccess(200,$totalAmount);
        }
    }

}