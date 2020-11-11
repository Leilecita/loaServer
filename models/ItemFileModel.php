<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 04/12/2018
 * Time: 12:38
 */

require_once 'BaseModel.php';
require_once 'ClientModel.php';

class ItemFileModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'items_file';
    }

    public function save($data)
    {
      //  $data['previous_balance']=$this->sum($data['client_id']);
        return parent::save($data); // TODO: Change the autogenerated stub
    }

    function getItemsFileClientEvents($dateSince,$dateTo){
        //balance es cuando ingresas un valor a la ficha que nose refleja en la caja del dia.

        $query = 'SELECT *, c1.created as client_created, i.created as item_file_created , i.product_type as product_type FROM clients c1 inner JOIN items_file i ON i.client_id = c1.id where
 i.created < \''.$dateTo.'\' and i.created >= \''.$dateSince.'\' and description != "Salida ficha" and balance = "false" ORDER BY item_file_created DESC';

        return $this->getDb()->fetch_all($query);

    }
    function getItemsFileClient($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT *, c1.created as client_created, i.created as item_file_created FROM clients c1 JOIN items_file i ON i.client_id = c1.id '.( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY item_file_created DESC';

        return $this->getDb()->fetch_all($query);
    }




    //ESTO ES PARA LA PARTE DE VENTAS, STOCK_EVENTS ->

    //balance = false se usa para los eventos de stock,
    // porque solo se muestra en la planilla de ventas los movimientos que repercutene n la caja de dia.
    function amountByDay($date1,$date2){
        $response = $this->getDb()->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' WHERE value > ? AND created >= ? AND created < ? AND balance = ? AND detail = ? ORDER BY created DESC',0.0,$date1,$date2,"false","Salida venta");
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    function amountByDateEf($date1,$date2,$payment_method){

        $response = $this->getDb()->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' WHERE value > ? AND created >= ? AND created < ? AND payment_method = ? AND balance = ? AND detail = ? ORDER BY created DESC',0.0,$date1,$date2,$payment_method,"false","Salida venta");
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    function amountByDateCardDeb($date1,$date2,$payment_method){
        $response = $this->getDb()->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' WHERE value > ? AND created >= ? AND created < ? AND payment_method != ? AND balance = ? AND detail = ? ORDER BY created DESC',0.0,$date1,$date2,$payment_method,"false","Salida venta");
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    //<-

}