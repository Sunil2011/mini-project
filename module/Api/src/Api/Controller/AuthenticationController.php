<?php
namespace Api\Controller;

use Zend\View\Model\JsonModel;
use Api\Controller\BaseController;
use Api\Form\LoginForm;
use Api\Model\Login;
use Zend\Session\Container;
use Zend\Crypt\Password\Bcrypt ;

class AuthenticationController extends BaseController
{
    protected $form;
    protected $authservice;
    protected $userTable;

    public function getAuthService()
    {
        if (!$this->authservice) {
            $this->authservice = $this->getServiceLocator()
                    ->get('AuthService');
        }
        return $this->authservice;
    }

    public function getForm()
    {
        if (!$this->form) {
            $this->form = new LoginForm();
        }
        return $this->form;
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Api\Model\UserTable');
        }
        return $this->userTable;
    }

    /**
     * @SWG\Post(
     *     path="/api/auth/login",
     *     description="login details",
     *     tags={"auth-user"},
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="username",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="password",
     *         required=true,
     *         type="string"
     *     ), 
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    
    /*  // this not in use now we are using Bcript for password 
    public function loginAction()
    {
        $form = $this->getForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $log = new Login();
            $form->setInputFilter($log->getInputFilter());
            $form->setData($data);

            if ($form->isValid()) {
                //check authentication... // set identity and credentials using login form,
                $this->getAuthService()->getAdapter()
                        ->setIdentity($data['username'])
                        ->setCredential($data["password"]);
                $result = $this->getAuthService()->authenticate();
                $authMsg = array();
                foreach ($result->getMessages() as $message) {
                    $authMsg[] = $message;
                }

                if ($result->isValid()) {
                    // get user array with :- id ,username , email for session storage
                    $data = $this->getUserTable()->getEmail($data['username'], $data['password']);
                    $this->createSession($data);
                    $account = array();
                    $account[id] = $data['id'];
                    $account['username'] = $data['username'];
                    $account['email'] = $data['email'];

                    return new JsonModel(array(
                        'success' => true,
                        'message' => 'authenticated',
                        'id' => $account['id'],
                        'account' => $account,
                            ));
                } else {
                    return new JsonModel(array(
                        'success' => false,
                        'message' => $authMsg,
                    ));
                }
            }
        }

        return new JsonModel(array(
            'success' => false,
            'msg' => 'no post data for authentication ..'
        ));
    }
    */
    
    public function loginAction()
    {
        $form = $this->getForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $log = new Login();
            $form->setInputFilter($log->getInputFilter());
            $form->setData($data);

            if ($form->isValid()) {
                //check user...
                $usr = $this->getUserTable()->getUserWithUsername($data);
                if ($usr) {
                    $bcrypt = new Bcrypt();
                    $securePass = $usr->password;
                    $pass = $data['password'];

                    if ($bcrypt->verify($pass, $securePass)) {
                        $this->createSession($usr);
                        $account = array();
                        $account['id'] = $usr['id'];
                        $account['username'] = $usr['username'];
                        $account['email'] = $usr['email'];

                        return new JsonModel(array(
                            'success' => true,
                            'message' => 'authenticated',
                            'id' => $account['id'],
                            'account' => $account,
                        ));
                    } else {

                        return new JsonModel(array(
                            'success' => false,
                            'message' => 'incorrect password',
                        ));
                    }
                } else {
                    return new JsonModel(array(
                        'success' => false ,
                        'message' => 'incorrect username '
                    ));
                }
            }
        }

        return new JsonModel(array(
            'success' => false,
            'msg' => 'no post data for authentication ..'
        ));
    }
    
    
    /**
     * @SWG\Get(
     *     path="/api/auth",
     *     description="index",
     *     tags={"auth-user"},
     *     
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function indexAction()
    {
        return new JsonModel(array(
            'success' => false,
            'msg' => "please check the api you are using "
        ));
    }

    /**
     * @SWG\Get(
     *     path="/api/auth/logout",
     *     description="logout",
     *     tags={"auth-user"},
     *     
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function logoutAction()
    {
        $this->getAuthService()->clearIdentity();
        $this->clearSession();

        return new JsonModel(array(
            'success' => true,
            'msg' => "You've successfully logged-out "
        ));
    }

    /**
     * @SWG\Get(
     *     path="/api/auth/me",
     *     description="auth",
     *     tags={"auth-user"},
     *     
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function meAction()
    {
        $sess = $this->checkUserSession();

        if (!$sess) {
            return new JsonModel(array(
                'success' => false,
                'message' => 'please log-in then try  ',
                'account' => null,
            ));
        } else {
            $user_session = new Container('user');
            $account['id'] = $user_session->id;
            $account['username'] = $user_session->username;
            $account['email'] = $user_session->email;

            return new JsonModel(array(
                'success' => true,
                'account' => $account,
            ));
        }
    }

}
