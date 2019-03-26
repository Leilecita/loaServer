<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 26/03/2019
 * Time: 10:38
 */

include 'controllers/EventsController.php';


$controller = new EventsController();

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