<?php
namespace handler\Listener;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Mvc\Application;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Di\Injectable;

class Listener extends injectable
{
    public function beforeHandleRequest(Event $event, Application $app, Dispatcher $dis)
    {
        $acl = new Memory();
        $acl->addRole('user');
        $acl->addRole('admin');
        $acl->addRole('accountant');

        $acl->addComponent(
            'index',
            [
                'index',
                'signup',
                'login',
                'doLogin'
            ]
        );

        $acl->addComponent(
            'user',
            [
                'index',
            ],
        );

        $acl->addComponent(
            'admin',
            [
                'index',
            ],
        );$acl->addComponent(
            'accounting',
            [
                'index',
            ]
        );
        $acl->allow('*', 'index', '*');
        $acl->allow('user', 'user', '*');
        $acl->allow('admin', '*', '*');
        $acl->allow('accountant', 'accounting', '*');

        $role = "user";
        $controller = "index";
        $action = "index";
        if (!empty($dis->getControllerName())) {
            $controller = $dis->getControllerName();
        }
        if (!empty($dis->getActionName())) {
            $action = $dis->getActionName();
        }
        if (!empty($this->request->get('role'))) {
            $role = $this->request->get('role');
        }
        if (false === $acl->isAllowed($role, $controller, $action)) {
            echo '<h3>Access denied !</h3>';
            echo 'Go to login page';
            echo '<a href = "/index/login">Login</a>';
            die;
        }
    }
}
