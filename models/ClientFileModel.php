<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 04/12/2018
 * Time: 12:35
 */

require_once 'BaseModel.php';

class ClientFileModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'clients_file';
    }

}