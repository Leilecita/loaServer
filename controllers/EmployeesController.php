<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 13/12/2018
 * Time: 16:31
 */

require_once 'BaseController.php';
require_once  __DIR__.'/../models/EmployeeModel.php';

class EmployeesController extends BaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new EmployeeModel();
    }
}