<?php

namespace Api\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where ;
use Zend\Db\Sql\Sql ;

class GroupMemberTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {   
        $where = new Where() ;
        $where->notEqualTo('flag', '-1');
        $resultSet = $this->tableGateway->select($where);
        return $resultSet;
    }
         
    public function getSpecificGroupMember($id)
    {   
       $where = new Where() ;
       $where->equalTo('id', $id) ;
       $where->notEqualTo('flag', '-1');
        
        $rowset = $this->tableGateway->select($where);
        $row = $rowset->current();
       
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        
        return $row;
    }
    
     
    public function getGroupMembers($group_id)
    {   
        $where = new Where() ;
        $where->equalTo('group_id', $group_id);
        $where->notEqualTo('flag', '-1');
        
        $resultSet = $this->tableGateway->select($where);
        
        $res = array();
        foreach($resultSet as $r ){
            $res['id'] = $r->id ;
            $res['group_id'] = $r->group_id ;
                        
            $res['user_id'] = $r->user_id ;
            $res['user_name'] = $r->user_name ;
            $res['email'] = $r->email ;
            
            $a[] = $res ;
        } 
        
        return $a ;
    }
    
    //now not in use
    public function getMyGroups($user_id){
        
        $where = new Where();
        $where->equalTo('user_id', $user_id);
        $where->notEqualTo('flag', '-1');
        
        $resultSet = $this->tableGateway->select($where);
        
        $res = array();
        foreach($resultSet as $r ){
            $res['id'] = $r->id ;
            $res['group_id'] = $r->group_id ;
                        
            $res['user_id'] = $r->user_id ;
            
            $a[] = $res ;
        } 
        
        return $a ;
    }
    

    public function saveGroupMember($grpm)
    {               
        if ( !isset($grpm['id']) ) {
            
            $this->tableGateway->insert($grpm);
            $id = $this->tableGateway->getLastInsertValue() ;
              
        } else {
            $dbData = $this->getSpecificGroupMember($grpm['id']) ; 
            if ($dbData) {
                $id = $grpm['id'] ;
                $this->tableGateway->update($grpm, array('id' => $id ));
            } else {
                throw new \Exception('Group Member id does not exist');
            }
        }
        
        $data = $this->getSpecificGroupMember($id);
        return $data ;
    }
    
    public function joinGroup($grpm)
    {
        $where = new Where();
        $where->equalTo('group_id', $grpm['group_id']);
        $where->equalTo('user_id', $grpm['user_id']);
        $where->notEqualTo('flag', '-1');

        $rowset = $this->tableGateway->select($where);
        $row = $rowset->current();
        
        if (!$row) {
            $this->tableGateway->insert($grpm);
            $id = $this->tableGateway->getLastInsertValue() ;
            
        } 
        
        return $id ;
    }

    public function deleteGroupMember($id)
    {
        //var_dump($id);
        try {
          $this->tableGateway->update(array('flag' => '-1'), array('group_id' => $id)); 
          return true ;
          
        } catch (Exception $ex) {
            return false ;  
        } 
        
    }
    
    public function getMyGroupsDetails($userId)
    {       
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('gmt'=>'groupMemberTable'));
        $select->columns(array('user_id','group_id'));
        $select->join(
            array('gp' => 'groupTable'),
            'gmt.group_id = gp.group_id',
             array('group_name','image','created_by','creator_email','created_at')   
        );
                
        $where = new Where();
        $where->equalTo('gmt.user_id', $userId);
        $select->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        
        $r = array();
        foreach ($results as $result) {
            $r = $result ;
            $rslt[] = $r ;
        }
        
        return $rslt ;
        
    }


    
    public function getOtherGroups($mygroupIds)
    {        
       // if $mygroupIds is null i.e. user is not a member of any group 
        if (!$mygroupIds) {
            $mygroupIds = array(0); 
        }
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('gt'=>'groupTable'));
        $select->columns(array('group_id','group_name',
            'group_image'=>'image',
            'created_by','creator_email','created_at')
        );
        $where = new Where();
        $where->notIn('gt.group_id', $mygroupIds) ;
        $select->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        
        $r = array();
        foreach ($results as $result) {
            $r = $result ;
            $rslt[] = $r ;
        }
        return $rslt ;
    }
   
}






