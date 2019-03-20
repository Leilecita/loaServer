<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 11/03/2019
 * Time: 16:20
 */


require_once 'BaseController.php';
require_once  __DIR__.'/../models/BoxModel.php';

class BoxesController extends BaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new BoxModel();
    }

}