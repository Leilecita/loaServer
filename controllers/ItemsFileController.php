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
class ItemsFileController extends BaseController
{
    private $clients;

    function __construct(){
        parent::__construct();
        $this->model = new ItemFileModel();
        $this->clients = new ClientModel();
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
                    'product_kind'=> $list_operations[$i]['product_kind'],'settled' =>$list_operations[$i]['settled']);
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

    public function post()
    {

        parent::post(); // TODO: Change the autogenerated stub
        $data = (array) json_decode(file_get_contents("php://input"));
        $this->updateDebtClient($data['client_id']);
    }

   public function put()
   {


       parent::put(); // TODO: Change the autogenerated stub

       $data = (array) json_decode(file_get_contents("php://input"));
       $this->updateDebtClient($data['client_id']);
   }

    public function delete()
    {
        $item_file=$this->getModel()->findById($_GET["id"]);
        parent::delete(); // TODO: Change the autogenerated stub
        $this->updateDebtClient($item_file['client_id']);
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


}