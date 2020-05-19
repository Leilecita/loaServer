<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 25/04/2019
 * Time: 16:17
 */
require_once __DIR__.'/SecureBaseController.php';
require_once  __DIR__.'/../models/UserModel.php';
class UsersControllers extends SecureBaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new UserModel();
    }


    public function get()
    {
        $this->beforeMethod();

        $user= $this->getCurrentUser();
        $this->returnSuccess(200,array("token" => $user['token']));
    }


}


