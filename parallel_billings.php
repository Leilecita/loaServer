<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 20/11/2020
 * Time: 15:58
 */

include 'controllers/ParallelBillingsController.php';


$controller = new ParallelBillingsController();

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