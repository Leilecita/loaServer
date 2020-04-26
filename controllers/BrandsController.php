<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 26/04/2020
 * Time: 12:00
 */

require_once 'SecureBaseController.php';
require_once  __DIR__.'/../models/BrandModel.php';

class BrandsController extends SecureBaseController
{
    function __construct(){
        parent::__construct();
        $this->model = new BrandModel();
    }
}

