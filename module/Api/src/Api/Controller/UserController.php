<?php

namespace Api\Controller;
use Api\Controller\BaseController;
use Zend\View\Model\JsonModel;
use Zend\Crypt\Password\Bcrypt;
use Zend\Session\Container;

class UserController extends BaseController
{
    protected $userTable;

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Api\Model\UserTable');
        }
        return $this->userTable;
    }

    /**
     * @SWG\Get(
     *     path="/api/user",
     *     description="get all users",
     *     tags={"user"},
     *    
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function getList()
    {
        $res = $this->getUserTable()->fetchAll();
        $a_res = array();

        foreach ($res as $r) {
            $a_res['id'] = $r->id;
            $a_res['username'] = $r->username;
            $a_res['email'] = $r->email;
            $a_res['dob'] = $r->dob ;
            $a_res['created_at'] = $r->created_at;
            $a_res['updated_at'] = $r->updated_at;
            $a[] = $a_res;  // creating multi. dim. array by adding all
        }

        return new JsonModel(array(
            'success' => true,
            'users' => $a,
        ));
    }

    /**
     * @SWG\Get(
     *     path="/api/user/{id}",
     *     description="category details",
     *     tags={"user"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="brand id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function get($id)
    {
        $sess = $this->checkUserSession();
        if (!$sess) {
            return new JsonModel(array(
                'success' => false,
                'message' => 'please log-in to get the details ',
            ));
        }

        $usrId = $id;
        if ($usrId) {
            try {
                $user = $this->getUserTable()->getUser($usrId);
            } catch (\Exception $ex) {
                return new JsonModel(array(
                    'success' => false,
                    'msg' => 'data with given id is not present in db !'
                ));
            }

            return new JsonModel(array(
                'success' => true,
                'user' => $user,
            ));
        } else {
            return new JsonModel(array(
                'success' => false,
                'message' => 'there is no data with id 0 !'
            ));
        }
    }

    /**
     * @SWG\Post(
     *     path="/api/user",
     *     description="create brand",
     *     tags={"user"},
     *   
     *     @SWG\Parameter(
     *         name="username",
     *         in="formData",
     *         description="user name",
     *         required=true,
     *         type="string"
     *     ),
     *    @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="email",
     *         required=true,
     *         type="string"
     *     ),
     *      @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         description="password",
     *         required=true,
     *         type="string"
     *     ),
     *       @SWG\Parameter(
     *         name="dob",
     *         in="formData",
     *         description="Date of Birth (yyyy-mm-dd)",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     *  ) 
     */
    public function create()
    {
        $parameter = $this->getParameter($this->params()->fromPost());
       // var_dump($parameter);exit;
        $usr = $this->getUserTable()->getUserWithUsernameAndEmail($parameter);
        
        if ($usr) {
            return new JsonModel(array(
                'success' => false,
                'message' => 'username-email already registered ! ',
            ));
        }
       
        $bcrypt = new Bcrypt();
        $securePass = $bcrypt->create($parameter['password']);
        $db = explode('-', $parameter['dob']);
        $dbM = $db[1];
        $dbD = $db[2];
        $data = array(
            'username' => $parameter['username'],
            'email' => $parameter['email'],            
            'password' => $securePass ,
            'dob' => $parameter['dob'],
            'dob_month' => $dbM ,
            'dob_day' => $dbD ,
            "created_at" => date('Y-m-d H:i:s'),
            'flag' => '0'
        );
       // var_dump($data); exit;        
        $Dt = $this->getUserTable()->saveUser($data);

        return new JsonModel(array(
            'success' => true,
            'user' => $Dt
        ));
    }

   /*
    public function deleteAction()
    {

        $parameter = $this->getParameter($this->params()->fromPost());
        $user_id = $parameter['id'];

        $msg = $this->getUserTable()->deleteUser($user_id);

        if ($msg) {
            return new JsonModel(array(
                'success' => true,
                'message' => 'deleted successfully '
            ));
        } else {
            return new JsonModel(array(
                'success' => false,
                'message' => 'unable to delete '
            ));
        }
    }
    */
    
    /**
     * @SWG\Post(
     *     path="/api/user/resetPassword",
     *     description="reset-password",
     *     tags={"user"},
     *   
     *     @SWG\Parameter(
     *         name="oldpassword",
     *         in="formData",
     *         description="current name",
     *         required=true,
     *         type="string"
     *     ),
     *    @SWG\Parameter(
     *         name="newpassword",
     *         in="formData",
     *         description="new-password",
     *         required=true,
     *         type="string"
     *     ),     
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     *  ) 
     */
    
    public function resetPasswordAction(){
       
       $sess = $this->checkUserSession();
        if (!$sess) {
            return new JsonModel(array(
                'success' => false,
                'message' => 'please log-in then try  ',
            ));
        } else {
            $usr = array();
            $user_session = new Container('user');
            $usr['userid'] = $user_session->id;
            $usr['username'] = $user_session->username;
            $usr['email'] = $user_session->email;
        }

        $parameter = $this->getParameter($this->params()->fromPost());
        $oldpassword = $parameter['oldpassword'];
        $newpassword = $parameter['newpassword'];
        
        if (!$oldpassword || !$newpassword) {
            return new JsonModel(array(
                'success' => false,
                'message' => 'please provide password and newpassword',
            ));
        }
                 
        $data = $this->getUserTable()->getUserWithUsername($usr);
        $bcrypt = new Bcrypt();
        $securePass = $data->password;
        $pass = $oldpassword;
        if ($bcrypt->verify($pass, $securePass)) {
            $secNewPass = $bcrypt->create($newpassword);
            $var = $this->getUserTable()->updatePass($usr, $secNewPass);

            if ($var) {
                return new JsonModel(array(
                    'success' => true,
                    'message' => 'password updated successfully !'
                ));
            }
        } else {
            return new JsonModel(array(
                'success' => false,
                'message' => 'incorrect current password',
            ));
        }
    }

    public function getParameter($params)
    {
        $parameter = array();
        foreach ($params as $key => $value) {
            if ($value) {
                $parameter[$key] = $value;
            }
        }
        return $parameter;
    }

}
