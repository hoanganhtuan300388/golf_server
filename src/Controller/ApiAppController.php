<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/21/2017
 * Time: 9:50 AM
 */
namespace App\Controller;

use Cake\Event\Event;
use Cake\Routing\Router;

class ApiAppController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }


    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
    }

}