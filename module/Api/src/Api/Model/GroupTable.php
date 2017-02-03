<?php

namespace Api\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;

//use Zend\Db\Sql\Select ;

class GroupTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $where = new Where();
        $where->notEqualTo('flag', '-1');
        $resultSet = $this->tableGateway->select($where);

        foreach ($resultSet as $r) {
            $a_res = array();

            $a_res['group_id'] = $r->group_id;
            $a_res['group_name'] = $r->group_name;
            $a_res['created_by'] = $r->created_by;
            $a_res['user_id'] = $r->user_id;
            $a_res['creator_email'] = $r->creator_email;
            $a_res['image'] = $r->image;
            $a_res['created_at'] = $r->created_at;
            $a_res['updated_at'] = $r->updated_at;

            $a[] = $a_res;  // creating multi. dim. array by adding all
        }

        return $a;
    }

    public function getGroup($id)
    {
        $where = new Where();
        $where->equalTo('group_id', $id);
        $where->notEqualTo('flag', '-1');
        $rowset = $this->tableGateway->select($where);
        $row = $rowset->current();

        if (!$row) {
            //throw new \Exception("Could not find row $id");
            return false ;
        }

        return $row;
    }

    public function saveGroup($grp)
    {

        if (!isset($grp['group_id'])) {
            $this->tableGateway->insert($grp);
            $group_id = $this->tableGateway->getLastInsertValue();
        } else {
            $dbData = $this->getGroup($grp['group_id']);
            if ($dbData) {
                $group_id = $grp['group_id'];
                $this->tableGateway->update($grp, array('group_id' => $group_id));
            } else {
                throw new \Exception('Group id does not exist');
            }
        }

        $data = $this->getGroup($group_id);
        return $data;
    }

    public function deleteGroup($id)
    {
        //var_dump($id);
        try {
            $this->tableGateway->update(array('flag' => '-1'), array('group_id' => $id));
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

}
