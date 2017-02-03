<?php

namespace Api\Controller;
  
//use Zend\Mvc\Controller\AbstractRestfulController;

use Api\Controller\BaseController ;
use Zend\View\Model\JsonModel;
use Zend\Session\Container ;

class GroupController extends BaseController
{    
    protected $groupTable;
    protected $groupMemberTable ;
     
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

    
    /**
     * @SWG\Get(
     *     path="/api/group",
     *     description="get all groups ",
     *     tags={"groups"},
     *    
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function getList()
    {        
        $res = $this->getGroupTable()->fetchAll();
                
        return new JsonModel(array(
            'success' => true,
            'groups' => $res,
        ));
    }

    /**
     * @SWG\Get(
     *     path="/api/group/{id}",
     *     description="group details",
     *     tags={"groups"},
     *     @SWG\Parameter(
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
    public function get($id)
    {    
        $grpId = $id ;
        if ($grpId) {
            try {
                $group = $this->getGroupTable()->getGroup($grpId);
                $group['group_members'] = $this->getGroupMemberTable()->getGroupMembers($grpId) ;
                  
            } catch (\Exception $ex) {
                return new JsonModel(array(
                    'success' => false,
                    'msg' => 'data with given id is not present in db !'
                ));
            }
            
            return new JsonModel(array(
                'success' => true,
                'group' => $group ,
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
     *     path="/api/group",
     *     description="create new group ",
     *     tags={"groups"},
     *   
     *     @SWG\Parameter(
     *         name="group_name",
     *         in="formData",
     *         description="group name  ",
     *         required=true,
     *         type="string"
     *     ),
     *    
     *    @SWG\Parameter(
     *         name="file",
     *         in="formData",
     *         description="group-image",
     *         required=false,
     *         type="file"
     *     ),
     *     
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     *  ) 
     */
    
    public function create()
    {
        $parameter = $this->getParameter($this->params()->fromPost());
        $sess = $this->checkUserSession();
        
        if (!$sess) {
            return new JsonModel(array(
                'success' => false,
                'message' => 'please log-in then try  ',
            ));
        } else {
            $user_session = new Container('user');
            $parameter['user_id'] = $user_session->id;
            $parameter['created_by'] = $user_session->username;
            $parameter['creator_email'] = $user_session->email;
        }

        $image = '';
        $File = $this->params()->fromFiles('file');

        if ($File) {
            $image = $this->upload();
        }

        $data = array(
            'group_name' => $parameter['group_name'], // from postdata
            'created_by' => $parameter['created_by'],
            'user_id' => $parameter['user_id'],
            'creator_email' => $parameter['creator_email'],
            'image' => $image,
            "created_at" => date('Y-m-d H:i:s'),
            'flag' => '0'
        );

        $gp = $this->getGroupTable()->saveGroup($data);

        $gpm_data = array(
            'group_id' => $gp['group_id'],
            'user_id' => $gp['user_id'],
            'user_name' => $gp['created_by'],
            'email' => $gp['creator_email'],
            "created_at" => date('Y-m-d H:i:s'),
            'flag' => '0'
        );
        $gpm = $this->getGroupMemberTable()->saveGroupMember($gpm_data);

        return new JsonModel(array(
            'success' => true,
            'group' => $gp
        ));
    }

    /* not using delete group 
    
    public function deleteAction(){
        
       $parameter = $this->getParameter($this->params()->fromPost());
       $group_id = $parameter['id'] ;
        
       $msg = $this->getGroupTable()->deleteGroup($group_id);

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
     * @SWG\Get(
     *     path="/api/group/myGroups",
     *     description="my groups  details",
     *     tags={"groups"},
     *    
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function myGroupsAction(){
       
       $parameter =  array() ; 
       $sess = $this->checkUserSession();
       if (!$sess) {
           return new JsonModel(array(
               'success' => false ,
               'message' => 'please log-in then try  ' ,
           ));
       } else {
           $user_session = new Container('user');
           $parameter['user_id'] = $user_session->id ;
           $parameter['user_name'] = $user_session->username ;
           $parameter['email'] = $user_session->email ;
       }
       /*
       $gps = $this->getGroupMemberTable()->getMyGroups($parameter['user_id']);
       
        if ($gps) {
            
            foreach($gps as $gp){
               $gpd = array();
               $gpd['user_id'] = $gp['user_id'];
               $gpd['group_id'] = $gp['group_id'];
            
               $g = $this->getGroupTable()->getGroup($gp['group_id']);
               $gpd['group_name'] = $g['group_name'];
               $gpd['image'] = $g['image'];
               $gpd['created_by'] = $g['created_by'];
               $gpd['creator_email'] = $g['creator_email'];
               $gpd['created_at'] = $g['created_at'];
               $gpdata[] = $gpd ; 
            }
            
            return new JsonModel(array(
                'success' => true,
                'mygroups' => $gpdata ,
            ));
            
        } else {
            return new JsonModel(array(
                'success' => false,
                'mygroups' => 'no-groups',
            ));
            
        }                
        */
       
       $gpd = $this->getGroupMemberTable()->getMyGroupsDetails($parameter['user_id']);
      
       if ($gpd) {
           return new JsonModel(array(
               'success' => true ,
               'mygroups' => $gpd
           ));
           
       } else {
            return new JsonModel(array(
                'success' => false,
                'mygroups' => 'no-groups',
            ));
       }
       
    }
    
    
    
    /**
     * @SWG\Get(
     *     path="/api/group/otherGroups",
     *     description="other group details",
     *     tags={"groups"},
     *    
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function otherGroupsAction()
    {
        
       $parameter =  array() ; 
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
           $parameter['email'] = $user_session->email ;
       }
       
       $mygps = $this->getGroupMemberTable()->getMyGroups($parameter['user_id']);
       $mygpsIds = array();
       foreach($mygps as $mg){
           array_push($mygpsIds, $mg['group_id']);
       }
       
       /*  // also working fine but involves multiple/nested quiries 
       $allgps = $this->getGroupTable()->fetchAll();
       $allgpsIds = array();
       foreach($allgps as $allg){
           array_push($allgpsIds , $allg['group_id']);
       }
       
       $othergpsIds = array_diff($allgpsIds, $mygpsIds); 
       //var_dump($mygps , $allgps , $mygpsIds , $allgpsIds , $othergpsIds); 
       
       foreach($othergpsIds as $oIds){
           $otgp = array();
           $otg = $this->getGroupTable()->getGroup($oIds);
           $otgp['group_id'] = $otg->group_id ;
           $otgp['group_name'] = $otg->group_name;
           $otgp['group_image'] = $otg->image;
           $otgp['created_by'] = $otg->created_by;
           $otgp['creator_email'] = $otg->creator_email ;
           $otgp['created_at'] = $otg->created_at ;
           
           $othergps[] = $otgp ;
       }
              
       
       return new JsonModel(array(
           'success' => true,
           'other_groups' => $othergps ,
       ));

        */
       
       $othgp = $this->getGroupMemberTable()->getOtherGroups($mygpsIds);
       
       if ($othgp) {
           return new JsonModel(array(
               'success' => true,
               'other_groups' => $othgp 
           ));
       } else {
           return new JsonModel(array(
               'success' => false ,
               'message' => 'no other group is there'
           ));
       }
       
    }
    
    
    /**
     * @SWG\Get(
     *     path="/api/group/joinGroup/{id}",
     *     description="join new group ",
     *     tags={"groups"},
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
    public function joinGroupAction(){
        
       $parameter =  array() ; 
       $parameter['group_id'] = $this->params()->fromRoute('id', 0);
       
       $sess = $this->checkUserSession();
       if (!$sess) {
           return new JsonModel(array(
               'success' => false ,
               'message' => 'please log-in then try  ' ,
           ));
       } else {
           $user_session = new Container('user');
           $parameter['user_id'] = $user_session->id ;
           $parameter['user_name'] = $user_session->username ;
           $parameter['email'] = $user_session->email ;
       }
      
       $parameter[created_at] =  date('Y-m-d H:i:s');
      
       if ($parameter['group_id'] && $parameter['user_id']) {
          $id = $this->getGroupMemberTable()->joinGroup($parameter);
           
          if ($id) {
            return new JsonModel(array(
               'success' => true ,
               'message' => 'you joined the group having group_id '.$parameter['group_id'] ,
            )); 
          } else {
              return new JsonModel(array(
                  'success' => false ,
                  'message' => 'data not inserted to groupMemberTable',
              ));
          }
                      
       } else {
           return new JsonModel(array(
               'success' => false,
               'message' => 'insufficient data '
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

    public function upload()
    {   
        $file = $this->params()->fromFiles('file');
        $uploadDir = PUBLIC_PATH.'/uploads/group/' ; 
        $uploadfile = $uploadDir .uniqid(). basename($file['name']); //uniqid() is added to complete-name of file  
        move_uploaded_file($file['tmp_name'], $uploadfile) ;
        
       // return $file['name'] ;
        return basename($uploadfile);
    }
    
    
}
