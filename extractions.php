<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 19/12/2018
 * Time: 17:35
 */

include 'controllers/ExtractionsController.php';


$controller = new ExtractionsController();

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