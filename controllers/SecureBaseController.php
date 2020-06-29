<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 29/04/2019
 * Time: 18:58
 */

require_once 'BaseController.php';
require_once __DIR__.'/../libs/SessionHelper.php';

abstract class SecureBaseController extends BaseController
{
    protected $currentUser;

    function __construct()
    {
        $this->currentUser = null;
    }

    function beforeMethod()
    {
        $this->_checkSession();
    }

    function _checkSession(){
        $this->currentUser = SessionHelper::getCurrentUser();
        if($this->getCurrentUser() == null) {
            error_log("checksession null");
            $this->returnError(401,'Session invalida');
            exit;
        }
    }

    function getUser(){
        $this->currentUser = SessionHelper::getCurrentUser();
        if($this->getCurrentUser() == null) {
           return null;
        }else{
            return $this->getCurrentUser();
        }
    }

    function getCurrentUser(){
        return $this->currentUser;
    }



}