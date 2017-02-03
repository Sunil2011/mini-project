<?php

namespace Api\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where ;

class UserTable
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
    
          
     
    public function getUser($id)
    {   
       
        $rowset = $this->tableGateway->select( array('id' => $id));
        $row = $rowset->current();
        $data = array(
            'id' => $row->id,
            'username' => $row->username,
            'email' => $row->email,
            'dob' => $row->dob,
            'flag' => $row->flag ,
            'created_at' => $row->created_at,
            'updated_at' => $row->updated_at
        );
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        
        // var_dump($row); exit;
        return $data;
    }
    
    // user with username and email
    public function getUserWithUsernameAndEmail($param)
    {   
        $where = new Where();
        $where->equalTo('username', $param['username']);
        $where->equalTo("email", $param['email']); 
        $rowset = $this->tableGateway->select($where);
        $row = $rowset->current();
       
        if (!$row) {
            return false  ;
        }
       
        return $row;
    }
    
     // user with username 
    public function getUserWithUsername($param)
    {   
        $where = new Where();
        $where->equalTo('username', $param['username']);
        $rowset = $this->tableGateway->select($where);
        $row = $rowset->current();
       
        if (!$row) {
            return false  ;
        }
        // var_dump($row); exit;
        return $row;
    }
    
    
    public function getEmail($username , $password)
    {   
        $where = new Where() ;
        $where->equalTo('username', $username);
        $where->equalTo('password', $password); 
        $rowset = $this->tableGateway->select( $where );
        $row = $rowset->current();
        
        $usr = array();
        $usr['id'] = $row['id'];
        $usr['username'] = $row['username'];
        $usr['email'] = $row['email'];
        
        return $usr ;
    }

    public function saveUser($usr)
    {               
        if ( !isset($usr['id']) ) {
            $this->tableGateway->insert($usr);
            $id = $this->tableGateway->getLastInsertValue() ;
              
        } else {
            $dbData = $this->getUser($usr['id']) ; 
            if ($dbData) {
                $id = $usr['id'] ;
                $this->tableGateway->update($usr, array('id' => $id ));
            } else {
                throw new \Exception('User id does not exist');
            }
        }
        
        $data = $this->getUser($id);
        return $data ;
    }

    public function updatePass($usr, $newpassword)
    {               
        try {
            $this->tableGateway->update(array('password' => $newpassword), array('id' => $usr['userid']));
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function deleteUser($id)
    {
        try {
            $this->tableGateway->update(array('flag' => '-1'), array('id' => $id));
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}



