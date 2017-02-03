<?php

namespace Api\Controller;

use Api\Controller\BaseController ;
use Zend\View\Model\JsonModel;
use Zend\Session\Container ;

class GroupChatController extends BaseController
{   
    protected $groupTable;
    protected $groupMemberTable ;
    protected $groupChatTable ;
        
    public function getGroupTable()
    {
        if (!$this->groupTable) {
            $sm = $this->getServiceLocator();
            $this->groupTable = $sm->get('Api\Model\GroupTable');
        }
        return $this->groupTable;
    }
    
    public function getGroupMemberTable()
    {
        if(!$this->groupMemberTable){
            $sm = $this->getServiceLocator();
            $this->groupMemberTable = $sm->get('Api\Model\GroupMemberTable');
        }
        return $this->groupMemberTable ;
    }
    
    public function getGroupChatTable()
    {
        if(!$this->groupChatTable){
            $sm = $this->getServiceLocator();
            $this->groupChatTable = $sm->get('Api\Model\GroupChatTable');
        }
        return $this->groupChatTable ;
    }

    
    public function indexAction(){
        
        return new JsonModel(array(
            'success' => true ,
            'message' => 'provide action and id to get or add  the group-chat   '
        ));
        
    }    

    /**
     * @SWG\Get(
     *     path="/api/group/chat/getGroupChat/{id}",
     *     description="get all group chat ",
     *     tags={"group-chat"},
     *    @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="group id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function getGroupChatAction()
    {    
        $sess = $this->checkUserSession();
        if( !$sess ){
           return new JsonModel(array(
               'success' => false ,
               'message' => 'please log-in then try  ' ,
           ));
        } else {
           $user_session = new Container('user');
           $parameter['user_id'] = $user_session->id ;
           $parameter['user_name'] = $user_session->username ;
           $parameter['user_email'] = $user_session->email ;
        }
        
        $group_id = $this->params()->fromRoute('id',0);
        
        if(!$group_id){
            return new JsonModel(array(
                'success' => false,
                'message' => 'provide group id to get the chats' ,
            ));
        }
        
        $gp = $this->getGroupTable()->getGroup($group_id); 
        if (!$gp) {
            return new JsonModel(array(
                'success' => false ,
                'message' => 'group with group_id : '.$group_id.' not found !'
            ));
        }
        
        $myUserId = $parameter['user_id'];
        $chats = $this->getGroupChatTable()->getGroupChat($group_id, $myUserId);
        
        return new JsonModel(array(
            'success' => true,
            'group_id' => $gp['group_id'],
            'group_name' => $gp['group_name'],
            'group_chats' => $chats,
        ));
    }    
    
    /**
     * @SWG\Post(
     *     path="/api/group/chat/addGroupChat",
     *     description="add chat",
     *     tags={"group-chat"},
     *   
     *     @SWG\Parameter(
     *         name="group_id",
     *         in="formData",
     *         description="group id  ",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="comment",
     *         in="formData",
     *         description="group comment ",
     *         required=true,
     *         type="string"
     *     ),
     * 
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     *  ) 
     */
    
    public function addGroupChatAction()
    {
       $postD = $this->getParameter($this->params()->fromPost());
       
       $parameter = array();
       $parameter['group_id'] = isset($postD['group_id']) ? $postD['group_id'] : 0 ;
       $parameter['comment'] = isset($postD['comment']) ? $postD['comment'] : 0 ;
       
       $sess = $this->checkUserSession();
       if( !$sess ){
           return new JsonModel(array(
               'success' => false ,
               'message' => 'please log-in then try  ' ,
           ));
       }else{
           $user_session = new Container('user');
           $parameter['user_id'] = $user_session->id ;
           $parameter['user_name'] = $user_session->username ;
           $parameter['user_email'] = $user_session->email ;
       }
              
        if($parameter['group_id']){
            
            if(!$parameter['comment']){
               return new JsonModel(array(
                  'success' => true,
                  'group_chat' => 'empty' 
               ));
            }
           
            //date_default_timezone_set("Asia/Kolkata");

            $data = array(
                'group_id' => $parameter['group_id'],                 
                'user_id' => $parameter['user_id'],
                'user_name' => $parameter['user_name'],                
                'comment' => $parameter['comment'] ,
                "created_at" => date('Y-m-d H:i:s'),
                'flag' => '0'
            );
            
            $gp = $this->getGroupChatTable()->saveGroupChat($data);
            
            return new JsonModel(array(
                'success' => true,
                'group_chat' => $gp ,
            ));
                    
        }
        
        else{
            return new JsonModel(array(
                'success' => false,
                'message' => 'set group_id '
            ));
        }           
     
    }
    
    /**
     * @SWG\Post(
     *     path="/api/group/chat/likeChat",
     *     description="like a comment",
     *     tags={"group-chat"},
     *   
     *     @SWG\Parameter(
     *         name="chat_id",
     *         in="formData",
     *         description="chat id  ",
     *         required=true,
     *         type="integer"
     *     ),     
     * 
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     *  ) 
     */
    public function likeChatAction()
    {
       $postD = $this->getParameter($this->params()->fromPost());
       
       $parameter = array();
       $parameter['chat_id'] = isset($postD['chat_id']) ? $postD['chat_id'] : 0 ;
       
       $sess = $this->checkUserSession();
       if( !$sess ){
           return new JsonModel(array(
               'success' => false ,
               'message' => 'please log-in then try  ' ,
           ));
       }else{
           $user_session = new Container('user');
           $parameter['user_id'] = $user_session->id ;
           $parameter['user_name'] = $user_session->username ;
           $parameter['user_email'] = $user_session->email ;
       }
       
       if (!$parameter['chat_id']) {
           return new JsonModel(array(
               'success' => false,
               'message' => 'chat_id is null',
           ));
       }
       
       $data = array(
           'chat_id' => $parameter['chat_id'],
           'user_id' => $parameter['user_id'],
           'created_at' => date('Y-m-d H:i:s')
       );
       
       $ms = $this->getGroupChatTable()->likeComment($data);
       
       if ($ms['success']) {
           $likedInfo = array(
               'user_id' => $ms['user_id'],
               'chat_id' => $ms['chat_id'],
               'isLiked' => ($ms['isLiked'] == 0) ? false : true ,
           ); 
           return new JsonModel(array(
               'success' => true,
               'info' => $likedInfo
           ));
           
       } else {
           return new JsonModel(array(
               'success' => false 
           ));
       } 
       
    }

    /**
     * @SWG\Get(
     *     path="/api/group/chat/getLikedChatDetails/{id}",
     *     description="get details of liked chat",
     *     tags={"group-chat"},
     *   
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="chat id ",
     *         required=true,
     *         type="integer"
     *     ),     
     * 
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     *  ) 
     */
    public function getLikedChatDetailsAction()
    {
       $chatId = $this->params()->fromRoute('id' , 0);
       $parameter['chat_id'] = $chatId ;
       $sess = $this->checkUserSession();
       if( !$sess ){
           return new JsonModel(array(
               'success' => false ,
               'message' => 'please log-in then try  ' ,
           ));
       } else {
           $user_session = new Container('user');
           $parameter['user_id'] = $user_session->id ;
           $parameter['user_name'] = $user_session->username ;
           $parameter['user_email'] = $user_session->email ;
       }
       
       if (!$parameter['chat_id']) {
           return new JsonModel(array(
               'success' => false,
               'message' => 'chat_id is null',
           ));
       }
       
       $msg = $this->getGroupChatTable()->likedCommentDetails($parameter['chat_id']);
       //var_dump($msg); exit;
       if (!$msg['success']) {
           return new JsonModel(array(
               'success' => false ,
               'message'=> 'no user liked this comment !'
               
           ));
       } else {
           $likes = count($msg['liked_users']);
           $param = array ('chatId'=>$chatId , 'userId'=>$parameter['user_id']);
           $isLikedByMe = $this->getGroupChatTable()->isLikedBy($param);
           
           return new JsonModel(array(
               'success' => true ,
               'chat_id' => $chatId ,
               'myUserId' => $parameter['user_id'],
               'myUserName' => $parameter['user_name'],
               'isLikedByMe' =>  $isLikedByMe ,
               'likes' => $likes ,
               'users_liked' => $msg['liked_users']
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

