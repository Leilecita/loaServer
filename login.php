<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 29/04/2019
 * Time: 19:01
 */

include 'controllers/LoginController.php';


$controller = new LoginController();

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