<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 18/06/2020
 * Time: 15:39
 */

include 'controllers/ParallelMoneyMovementsController.php';


$controller = new ParallelMoneyMovementsController();

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