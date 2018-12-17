<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 04/12/2018
 * Time: 12:35
 */

require_once 'BaseController.php';
require_once  __DIR__.'/../models/ClientFileModel.php';
class ClientsFileController extends BaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new ClientFileModel();
    }


}