<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 12/10/2020
 * Time: 13:09
 */

include 'controllers/PriceEventsController.php';


$controller = new PriceEventsController();

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