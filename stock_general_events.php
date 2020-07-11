<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 09/07/2020
 * Time: 11:56
 */
include 'controllers/StockGeneralEventsController.php';


$controller = new StockGeneralEventsController();

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