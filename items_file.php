<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 04/12/2018
 * Time: 12:29
 */

include 'controllers/ItemsFileController.php';


$controller = new ItemsFileController();

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