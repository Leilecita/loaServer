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

    function save($data){
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

            // $this->updateTransactions($id,$data['name']);

            return $this->getDb()->update($this->tableName, $data,['id' => "$id"]);
        }else{
            $filePath = '/uploads/users/'.time().'.jpg';
            $this->base64_to_jpeg($data['imageData'],__DIR__.'/..'.$filePath);
            unset($data['imageData']);
            $data['image_url'] = $filePath;

            return $this->getDb()->update($this->tableName, $data,['id' => "$id"]);
        }
    }

}