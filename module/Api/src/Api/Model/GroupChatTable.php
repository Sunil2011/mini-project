<?php

namespace Api\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where ;
use Zend\Db\Sql\Sql ;

class GroupChatTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getGroupChat($group_id, $myUserId)
    {   
        $where = new Where() ;
        $where->equalTo('group_id', $group_id);
        $where->notEqualTo('flag', '-1');
        $resultSet = $this->tableGateway->select($where);
        
        foreach($resultSet as $r ){
            $a_res = array();
            $a_res['chat_id'] = $r->id ;
            $a_res['group_id'] = $r->group_id;
            $a_res['user_id'] = $r->user_id;
            $a_res['user_name'] = $r->user_name;
            $a_res['comment'] = $r->comment ; 
            $a_res['likes'] = $r->likes;
           
            $a_res['liked_by'] = $r->liked_by ;
            $lkb = explode(',', $a_res['liked_by']);
            $var = array_search($myUserId, $lkb); // return false if not found 
            // return true in case of key found in our case key is never 0 bcz of data type 
            
            if ($var) {
                $a_res['isLikedByMe'] = true ;
            } else {
                $a_res['isLikedByMe'] = false ;
            }
            
            $a_res['created_at'] = $r->created_at ;
            $a_res['updated_at'] = $r->updated_at ;
            
            $a[] = $a_res;  // creating multi. dim. array by adding all
        }
        
        return $a ;
    }
    
          
     


    public function saveGroupChat($chatData)
    {        
       
        $this->tableGateway->insert($chatData);
        $chat_id = $this->tableGateway->getLastInsertValue() ;     
        
        $data = $this->getSpecificGroupChat($chat_id);
        return $data ;
    }

    
    public function getSpecificGroupChat($chat_id)
    {
        $where = new Where() ;
        $where->equalTo('id', $chat_id);
        $where->notEqualTo('flag', '-1');
        $rowset = $this->tableGateway->select($where);
        $row = $rowset->current();
       
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
         
        return $row;
        
    }
    
    public function likeComment($data)
    {
       // var_dump($data); exit;
        if (!$data['user_id'] || !$data['chat_id']) {
            $retrnData = array(
                'success' => false 
            );
            return $retrnData ;
        }
        
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('likeTable');
        $where = new Where();
        $where->equalTo('chat_id', $data['chat_id']);
        $where->equalTo('user_id', $data['user_id']);        
        $select->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        foreach ($result as $re){
            $res[] = $re ;
        }
        
        if (!$res) {
            $data['isLiked'] = 1 ;
            $sql = new Sql($adapter);
            $insert = $sql->insert();            
            $insert->into('likeTable');
            $insert->values($data);
            $statement = $sql->prepareStatementForSqlObject($insert);
            $result = $statement->execute();
           
            // updating groupChatTable
            $chat = $this->getSpecificGroupChat($data['chat_id']);
            $likes = (int) $chat->likes + 1;
            $liked_by = $chat->liked_by.','.$data['user_id'];
            $updData = array('likes'=>$likes,'liked_by'=>$liked_by);
            $this->tableGateway->update($updData, array('id'=>$data['chat_id']));
            
            $retrnData = array(
                'success' => true,
                'user_id' => $data['user_id'],
                'chat_id' => $data['chat_id'],
                'isLiked' => $data['isLiked']
            );
            return $retrnData ;
            
        } else {
            $param = array();
            $param['isLiked'] = ($res[0]['isLiked'] == 0) ? 1 : 0 ;            
            $sql = new Sql($adapter);
            $update = $sql->update();
            $update->table('likeTable');
            $update->set($param);
            $where = new Where();
            $where->equalTo('chat_id', $data['chat_id']);
            $where->equalTo('user_id', $data['user_id']);
            $update->where($where);
            $statement = $sql->prepareStatementForSqlObject($update);
            $result = $statement->execute();
           
            //updating groupChattable
            $chat = $this->getSpecificGroupChat($data['chat_id']);
            if ($param['isLiked'] == 1) {               
                $likes = (int) $chat->likes + 1;
                $liked_by = $chat->liked_by.','.$data['user_id'];
                $updData = array('likes'=>$likes,'liked_by'=>$liked_by);
                $this->tableGateway->update($updData, array('id'=>$data['chat_id']));
            } else {
                $likes = (int) $chat->likes - 1;
                $var = ','.$data['user_id'];
                $liked_by = str_replace($var, '', $chat->liked_by); // user_id, is removed from string
                $updData = array('likes'=>$likes,'liked_by'=>$liked_by);
                $this->tableGateway->update($updData, array('id'=>$data['chat_id']));
            }
            
            $retrnData = array(
                'success' => true,
                'user_id' => $data['user_id'],
                'chat_id' => $data['chat_id'],
                'isLiked' => $param['isLiked']
            );
            return $retrnData ;
        }
        
    }
    
    
    public function likedCommentDetails($chatId)
    {
        //var_dump($chatId); exit;
        if (!$chatId) {
             $retrnData = array(
                'success' => false 
            );
            return $retrnData ;
        }
        
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('lt'=>'likeTable'));
        $select->columns(array('chat_id','user_id','isLiked','liked_at'=>'updated_at'));
        $select->join(array('usr' => 'user'), 
                'lt.user_id = usr.id',
                array('username','email')
        );
        $where = new Where();
        $where->equalTo('lt.chat_id', $chatId);
        $where->equalTo('lt.isLiked', 1);
        $select->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        foreach ($result as $re){
            $res[] = $re ;
        }
        
        $retrnData = array(
            'success'=> true ,
            'liked_users' => $res 
        );
        return $retrnData ;
    }
    
    public function isLikedBy($data )
    {   
        $chatId = $data['chatId'];
        $userId = $data['userId'];
        if (!$chatId || !$userId) {
             $retrnData = array(
                'success' => false 
            );
            return $retrnData ;
        }
        
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from(array('lt'=>'likeTable'));        
        $where = new Where();
        $where->equalTo('lt.chat_id', $chatId);
        $where->equalTo('lt.user_id', $userId);
        $where->equalTo('lt.isLiked', 1);
        $select->where($where);
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        foreach ($result as $re){
            $res[] = $re ;
        }
        if (!empty($res)) {
            return true ;
        } else {
            return false ;
        }
        
    }
    

}





