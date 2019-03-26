<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 26/03/2019
 * Time: 10:39
 */

require_once 'BaseModel.php';
class EventModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'events';
    }


}