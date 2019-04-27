<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 25/04/2019
 * Time: 16:17
 */
require_once 'BaseController.php';
require_once  __DIR__.'/../models/UserModel.php';
class UsersControllers extends BaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new UserModel();
    }
}

