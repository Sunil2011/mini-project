<?php

namespace Api\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql ;
use Zend\Db\Sql\Where;

class BirthdayTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAllBday()
    {   
        /*
        $where = new Where();
        $where->notEqualTo('flag', '-1');
        $results = $this->tableGateway->select($where);
        */                
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('user');
        $where = new Where();
        $where->notEqualTo('flag', '-1');
        $select->where($where);
        $select->order(array('dob_month ASC','dob_day ASC'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        
        $res = array();
        foreach ($results as $r) {
            $a = array();
            $a['id'] = $r['id'];
            $a['username'] = $r['username'];
            $a['email'] = $r['email'];
            $a['dob'] = $r['dob'];
            $a['dob_month'] = $r['dob_month'];
            $a['dob_day'] = $r['dob_day'];
            $m = $this->findMonth($r['dob_month']);
            $res[$m][] = $a;
           // $res[$r['dob_month']]['month'] = $m ;
        }

        return $res;
    }
    
    public function specificMonthBday($monthIndex)
    {
        //$mnth = $this->findMonth($monthIndex);        
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('user');
        $where = new Where();
        $where->equalTo('dob_month', $monthIndex);
        $where->notEqualTo('flag', '-1');
        $select->where($where);
        $select->order(array('dob_day ASC'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        
        $res = array();
        foreach ($results as $r) {
            $a = array();
            $a['id'] = $r['id'];
            $a['username'] = $r['username'];
            $a['email'] = $r['email'];
            $a['dob'] = $r['dob'];
            $a['dob_month'] = $r['dob_month'];
            $a['dob_day'] = $r['dob_day'];            
            $res[] = $a;
        }
        return $res ;
    }
    
    public function in30daysBday()
    {
        $dt = getdate();
        $day = $dt['mday'];
        $month = $dt['mon'];
        $year = $dt['year']; 
       
        $dt1 = date_create($day.'-'.$month.'-'.$year);
        date_add($dt1, date_interval_create_from_date_string("30 days"));
        $dt2 = date_format($dt1, "Y-m-d");       
        $var = explode("-", $dt2);        
        $dayf = $var[2]; // in dd format
        $monthf = $var[1]; // in mm format
        
        $adapter = $this->tableGateway->getAdapter();
        $sql = new Sql($adapter);
        $select = $sql->select();
        $select->from('user');
        $where = new Where();
        $where->between('dob_month', $month, $monthf);
        $where->notEqualTo('flag', '-1');
        $select->where($where);
        $select->order(array('dob_month ASC','dob_day ASC'));
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        
        $res = array();
        foreach ($results as $r) {
            if (($r['dob_month'] == $month && $r['dob_day'] >= $day) || ($r['dob_month'] == $monthf && $r['dob_day'] <= $dayf)) {
                $a = array();
                $a['id'] = $r['id'];
                $a['username'] = $r['username'];
                $a['email'] = $r['email'];
                $a['dob'] = $r['dob'];
                $m = $this->findMonth($r['dob_month']);
                $a['dob_month'] = $m;
                $a['dob_day'] = $r['dob_day'];
                $res[] = $a;
            }
        }
       
        return $res ;
    }

    public function findMonth($index)
    {
        $months = array(
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12,
        );
        $mnth = array_search($index, $months);
        return $mnth ;
        
    }

}
