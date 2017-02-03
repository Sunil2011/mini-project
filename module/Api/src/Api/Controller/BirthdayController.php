<?php

namespace Api\Controller;
use Api\Controller\BaseController;
use Zend\View\Model\JsonModel;

class BirthdayController extends BaseController
{
    protected $bdayTable;
    
    public function getBirthdayTable()
    {
        if (!$this->bdayTable) {
            $sm = $this->getServiceLocator();
            $this->bdayTable = $sm->get('Api\Model\BirthdayTable');
        }
        return $this->bdayTable;
    }
    
    /**
     * @SWG\Get(
     *     path="/api/users-bday",
     *     description="get all users birthday's",
     *     tags={"users-bday"},
     *    
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function getList()
    {
        $sess = $this->checkUserSession();
        if (!$sess) {
            return new JsonModel(array(
                'success' => false,
                'message' => 'please log-in then try  ',
            ));
        }
        
        $bdays = $this->getBirthdayTable()->fetchAllBday();
        return new JsonModel(array(
            'success' => true,
            'bdays' => $bdays
        ));
    }

    /**
     * @SWG\Get(
     *     path="/api/users-bday/month/{id}",
     *     description="get birthday's in particular month ",
     *     tags={"users-bday"},
     *    @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="month index",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function monthAction()
    {
        $sess = $this->checkUserSession();
        if (!$sess) {
            return new JsonModel(array(
                'success' => false,
                'message' => 'please log-in then try  ',
            ));
        }

        $monthIndex = (int)$this->params()->fromRoute('id', 0); 
        if ($monthIndex>12 || $monthIndex <1){
            return new JsonModel(array(
                'success' => false,
                'message' => 'please enter correct month-index ',
            ));
        }
        
        $bdays = $this->getBirthdayTable()->specificMonthBday($monthIndex);
        return new JsonModel(array(
            'success' => true,
            'month' => $this->getBirthdayTable()->findMonth($monthIndex),
            'bdays' => $bdays
        ));
    }

    /**
     * @SWG\Get(
     *     path="/api/users-bday/in30days",
     *     description="get birthday's in next 30 days ",
     *     tags={"users-bday"},
     *   
     *     @SWG\Response(
     *         response=200,
     *         description="response"
     *     )
     * )
     */
    public function in30daysAction()
    {
        $sess = $this->checkUserSession();
        if (!$sess) {
            return new JsonModel(array(
                'success' => false,
                'message' => 'please log-in then try  ',
            ));
        }
        
        $bdays = $this->getBirthdayTable()->in30daysBday();
        return new JsonModel(array(
            'success' => true,
            'bdays' => $bdays
        ));
        
    }
}

