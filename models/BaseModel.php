<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 04/12/2018
 * Time: 11:38
 */
include __DIR__ . '/../config/config.php';
require __DIR__ . '/../libs/dbhelper.php';

use vielhuber\dbhelper\dbhelper;

abstract class BaseModel
{
    protected $tableName  = '';
    private $db;

    function __construct(){
        global $DBCONFIG;
        $this->db = new dbhelper();
        //$this->db->connect('pdo', 'mysql', '127.0.0.1', 'root', null, 'loa', 3306);
        $this->db->connect('pdo', 'mysql', $DBCONFIG['HOST'], $DBCONFIG['USERNAME'], $DBCONFIG['PASSWORD'],$DBCONFIG['DATABASE'],$DBCONFIG['PORT']);
    }

    function findById($id){
        return $this->db->fetch_row('SELECT * FROM '.$this->tableName.' WHERE id = ?',$id);
    }

    function findByIdAndZone($filters=array()){
        $conditions = join(' AND ',$filters);
        return $this->db->fetch_row('SELECT * FROM '.$this->tableName.( empty($filters) ?  '' : ' WHERE '.$conditions ));
    }

    public function getDb(){
        return $this->db;
    }

    function findAll($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->db->fetch_all($query);
    }

    function findAllByDate($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC';

        return $this->db->fetch_all($query);
    }

    function findAllByName($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY name ASC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->db->fetch_all($query);
    }

    function findAllByDebt($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY debt ASC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->db->fetch_all($query);
    }

    function findAllByClientId($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->db->fetch_all($query);
    }

    function findAllByEmployeeId($filters=array(),$paginator=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC LIMIT '.$paginator['limit'].' OFFSET '.$paginator['offset'];
        return $this->db->fetch_all($query);
    }

   /* function findAllByClientId($filters=array()){
        $conditions = join(' AND ',$filters);
        $query = 'SELECT * FROM '.$this->tableName .( empty($filters) ?  '' : ' WHERE '.$conditions ).' ORDER BY created DESC';

        return $this->db->fetch_all($query);
    }*/

    function sum($client_id){
        $response = $this->db->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' WHERE client_id = ? ORDER BY created ASC',$client_id);
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    function sumAllOperations(){
        $response = $this->db->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' ORDER BY created ASC');
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    function sumPreviousBalance($client_id,$created){
        $response = $this->db->fetch_row('SELECT SUM(value) AS total FROM '.$this->tableName.' WHERE client_id = ? AND created < ? ORDER BY created DESC',$client_id,$created);
        if($response['total']!=null){
            return $response;
        }else{
            $response['total']=0;
            return $response;
        }
    }

    function save($data){
        return $this->db->insert($this->tableName, $data );
    }

    function update($id, $data){
        return  $this->db->update($this->tableName, $data,['id' => "$id"]);
    }

    function delete($id){
        return ($this->db->delete($this->tableName, ['id' => $id]) == 1);
    }

    function deleteByOrderId($order_id){
        return ($this->db->delete($this->tableName, ['order_id' => $order_id]) == 1);
    }

    function deleteAll($order_id){
        return ($this->db->delete($this->tableName, ['order_id' => $order_id]));
    }

    function base64_to_jpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' );

        fwrite( $ifp, base64_decode( $base64_string ) );

        // clean up the file resource
        fclose( $ifp );

        return $output_file;
    }

}