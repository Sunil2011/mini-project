<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;

class BaseController extends AbstractRestfulController
{

    public function createSession($data)
    {
        $user_session = new Container('user');
        $user_session->id = $data['id'];
        $user_session->username = $data['username'];
        $user_session->email = $data['email'];
    }

    public function checkUserSession()
    {
        $user_session = new Container('user');
        $data = array();
        $data['id'] = isset($user_session->id) ? isset($user_session->id) : 0;
        $data['username'] = isset($user_session->username) ? isset($user_session->username) : 0;
        $data['email'] = isset($user_session->email) ? $user_session->email : 0;

        // var_dump($data['username']); exit;
        if ($data['username'] == 0) {
            return false;
        } else {
            return true;
        }
    }

    public function clearSession()
    {
        $user_session = new Container('user');
        $user_session->getManager()->getStorage()->clear('user');
    }

    public function successRes($msg, $data = array())
    {
        return new JsonModel(array(
            'success' => true,
            'message' => $msg,
            'data' => $data
        ));
    }

    public function errorRes($msg, $error = array(), $code = 500)
    {
        $this->getResponse()->setStatusCode($code);
        return new JsonModel(array(
            'error' => array_merge(
                    array(
                "type" => "Api\\Exception\\ApiException",
                'message' => $msg,
                "code" => $code
                    ), $error
            ),
        ));
    }

}
