<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 26/04/2020
 * Time: 14:00
 */

require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/TypeModel.php';

class TypesController extends SecureBaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new TypeModel();
    }
}
