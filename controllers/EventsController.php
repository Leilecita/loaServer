<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 26/03/2019
 * Time: 10:38
 */


require_once "SecureBaseController.php";
require_once __DIR__.'/../models/EventModel.php';

class EventsController extends SecureBaseController {

    function __construct() {
        parent::__construct();
        $this->model = new EventModel();
    }
}