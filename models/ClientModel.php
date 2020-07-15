<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 04/12/2018
 * Time: 11:47
 */

require_once 'BaseModel.php';

class ClientModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'clients';
    }


    function findAllClientName(){
        $query='SELECT id,name,created FROM clients where alive = "true" order by created desc';
        return $this->getDb()->fetch_all($query);
    }

    function findAllByName($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY name ASC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);
    }

    function findAllByDebt($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY debt ASC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);
    }

    function findAllClients($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->getDb()->fetch_all($query);
    }

   /* function save($data){
        if(empty($data['imageData'])) {
            unset($data['imageData']);
            return $this->getDb()->insert($this->tableName, $data);
        }else{
            $filePath = '/uploads/users/'.time().'.jpg';
            $this->base64_to_jpeg($data['imageData'],__DIR__.'/..'.$filePath);
            unset($data['imageData']);
            $data['image_url'] = $filePath;

            return $this->getDb()->insert($this->tableName, $data);
        }
    }
    function update($id,$data){

        error_log(print_r($data,true));
        if(empty($data['imageData'])) {
            unset($data['imageData']);

            return $this->getDb()->update($this->tableName, $data,['id' => "$id"]);
        }else{
            $filePath = '/uploads/users/'.time().'.jpg';
            $this->base64_to_jpeg($data['imageData'],__DIR__.'/..'.$filePath);
            unset($data['imageData']);
            $data['image_url'] = $filePath;

            return $this->getDb()->update($this->tableName, $data,['id' => "$id"]);
        }
    }*/

}