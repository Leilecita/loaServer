<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 04/12/2018
 * Time: 12:39
 */


require_once 'BaseController.php';
require_once  __DIR__.'/../models/ItemFileModel.php';
require_once  __DIR__.'/../models/ClientModel.php';
require_once  __DIR__.'/../models/EventModel.php';
class ItemsFileController extends BaseController
{
    private $clients;
    private $events;

    function __construct(){
        parent::__construct();
        $this->model = new ItemFileModel();
        $this->clients = new ClientModel();
        $this->events = new EventModel();
    }

    function listDebtsByClientId()
    {
        if (isset($_GET['client_id'])) {

            $operationsReport = array();
            $list_operations = $this->model->findAllByClientId(array('client_id = "' . $_GET['client_id'] . '"'),$this->getPaginator());
            for ($i = 0; $i < count($list_operations); ++$i) {


                $client = $this->clients->findById($list_operations[$i]['client_id']);

                $previous_balance=$this->calculatePreviousBalance($client['id'],$list_operations[$i]['created']);

                $operationsReport[] = array('name' => $client['name'], 'description' => $list_operations[$i]['description'],
                    'value' => $list_operations[$i]['value'], 'created' => $list_operations[$i]['created'],'item_file_id' =>
                $list_operations[$i]['id'],'observation' => $list_operations[$i]['observation'],
                    'brand'=> $list_operations[$i]['brand'],
                    'code'=> $list_operations[$i]['code'],
                    'size'=> $list_operations[$i]['size'],
                    'previous_balance'=>$previous_balance['total'],
                    'product_kind'=> $list_operations[$i]['product_kind'],'settled' =>$list_operations[$i]['settled'],
                    'client_id' =>$list_operations[$i]['client_id']);
            }
            $this->returnSuccess(200, $operationsReport);
        } else {
            $this->returnError(404, "ENTITY NOT FOUND");
        }
    }

    function calculatePreviousBalance($id,$created){
        return $this->model->sumPreviousBalance($id,$created);

    }

    function amountByClientId(){

        $totalAmount=$this->getModel()->sum($_GET['client_id']);
        $this->returnSuccess(200,$totalAmount);

    }

    function totalAmount(){

        $totalAmount=$this->getModel()->sumAllOperations();
        $this->returnSuccess(200,$totalAmount);

    }

    public function post()
    {

        parent::post(); // TODO: Change the autogenerated stub
        $data = (array) json_decode(file_get_contents("php://input"));
        $this->updateDebtClient($data['client_id']);
        $this->generateLogevent($data,"Creado","","");
    }

   public function put()
   {
       $data = (array) json_decode(file_get_contents("php://input"));

       $previous=$this->getModel()->findById($data['id']);
       $previous_desc=$previous['observation']." ".$previous['product_kind']." ".$previous['brand']." ".$previous['size']." ".$previous['code'];

       parent::put(); // TODO: Change the autogenerated stub

       $this->updateDebtClient($data['client_id']);
       $this->generateLogevent($data,"Modificado",$previous_desc,$previous['value']);
   }

    public function delete()
    {
        $item_file=$this->getModel()->findById($_GET["id"]);
        parent::delete(); // TODO: Change the autogenerated stub
        $this->updateDebtClient($item_file['client_id']);
        $this->generateLogevent($item_file,"Eliminado","","");
    }



    function updateDebtClient($id){
        if($this->clients->findById($id)){
            $totalAmount= $this->getModel()->sum($id);
            $this->clients->update($id,array('debt'=> $totalAmount));
        }
    }

    public function get()
    {
       if(isset($_GET['method'])){
           $this->method();
       }else{
           parent::get();
       }
    }
    function logEvent($employee_name,$description,$value,$previous_description,$previous_value,$state,$client_id){
        $this->events->save(array('employee_name' => $employee_name, 'description' => $description, 'value' => $value,'state' => $state,
            'previous' => $previous_description, 'previous_value' => $previous_value,'client_id' => $client_id));
    }

    function generateLogevent($data,$state,$previous_descr,$previous_value){

        $client= $this->clients->findById($data['client_id']);
        $value=$data['value'];
        $desc=$client['name']."".$data['observation']." ".$data['product_kind']." ".$data['brand']." ".$data['size']." ".$data['code'];
        $this->logEvent($client['employee_creator_id'],$desc,$value,$previous_descr,$previous_value,$state,$data['client_id']);
    }



}