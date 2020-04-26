<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 26/04/2020
 * Time: 11:59
 */
include 'controllers/BrandsController.php';


$controller = new BrandsController();

$method = $_SERVER['REQUEST_METHOD'];


switch ($method) {
    case 'GET':
        $controller->get();
        break;
    case 'POST':
        $controller->post();
        break;
    case 'DELETE':
        $controller->delete();
        break;
    case 'PUT':
        $controller->put();
        break;
    default:
        $controller->returnError(400,'INVALID METHOD');
        break;
}