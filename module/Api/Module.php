<?php

namespace Api ;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
//use Zend\ModuleManager\Feature\ConfigProviderInterface;


 use Api\Model\UserTable ;
 use Api\Model\GroupTable ;
 use Api\Model\GroupMemberTable ;
 use Api\Model\GroupChatTable ;
 use Api\Model\BirthdayTable ;
 
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;

//use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;


 
class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig(){
        return array(
             'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
             ),
             'Zend\Loader\StandardAutoloader' => array(
                 'namespaces' => array(
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                 ),
             ),
         );
        
    }
    
    
    public function getConfig(){ 
        
         return include __DIR__ . '/config/module.config.php';
    }
    
    
    public function getServiceConfig(){
        
    return array(
            'factories' => array(
                'Api\Model\UserTable' => function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                    // here user is db table name 
                },
                'Api\Model\BirthdayTable' => function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new BirthdayTable($tableGateway);
                    return $table;
                },        
                                                         
                 // now not in use       
                'AuthService' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 
                                              'user','username','password');
             
                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    
                    return $authService;
                    
                } ,
                        
                 
                'Api\Model\GroupTable' => function($sm) {
                    $tableGateway = $sm->get('GroupTableGateway');
                    $table = new GroupTable($tableGateway);
                    return $table;
                },
                'GroupTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new Group());
                    return new TableGateway('groupTable', $dbAdapter, null, $resultSetPrototype);
                    
                },
                        
                'Api\Model\GroupMemberTable' => function($sm) {
                    $tableGateway = $sm->get('GroupMemberTableGateway');
                    $table = new GroupMemberTable($tableGateway);
                    return $table;
                },
                'GroupMemberTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('groupMemberTable', $dbAdapter, null, $resultSetPrototype);
                    
                }, 
                
                'Api\Model\GroupChatTable' => function($sm) {
                    $tableGateway = $sm->get('GroupChatTableGateway');
                    $table = new GroupChatTable($tableGateway);
                    return $table;
                },
                'GroupChatTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    // $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('groupChatTable', $dbAdapter, null, $resultSetPrototype);
                    
                },        
                                                
            )
        );
    }
    
}
    
    

