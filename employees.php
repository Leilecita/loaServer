<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 13/12/2018
 * Time: 16:30
 */

include 'controllers/EmployeesController.php';


$controller = new EmployeesController();

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