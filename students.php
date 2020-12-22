<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 22/12/2020
 * Time: 17:03
 */

include 'controllers/StudentsController.php';


$controller = new StudentsController();

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