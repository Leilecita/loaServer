<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 04/12/2018
 * Time: 12:35
 */

require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/ClientFileModel.php';
class ClientsFileController extends SecureBaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new ClientFileModel();
    }


}