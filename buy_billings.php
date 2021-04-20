<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 19/04/2021
 * Time: 20:23
 */

include 'controllers/BuyBillingsController.php';


$controller = new BuyBillingsController();

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
        $controller->returnError(400, 'INVALID METHOD');
        break;
}