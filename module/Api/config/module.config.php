<?php

return array(
     'controllers' => array(
         'invokables' => array(
            'Api\Controller\User' => 'Api\Controller\UserController', 
            'Api\Controller\Authentication' => 'Api\Controller\AuthenticationController',
            'Api\Controller\Group' => 'Api\Controller\GroupController' ,
            'Api\Controller\GroupChat'  => 'Api\Controller\GroupChatController' ,
            'Api\Controller\Birthday' => 'Api\Controller\BirthdayController' , 
         ),
     ),
    
    
    'router' => array(
        'routes' => array(
               
            
            'user-details' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'=> '/api/user[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\User',
                        
                    ),
                ),
            ),
            
            
            'auth-rest' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'=> '/api/auth[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',                        
                         
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\Authentication',
                        'action' => 'index' ,
                                                
                    ),
                ),
            ),
            
            
            'api-groups' => array(
                'type' => 'Segment' ,
                'options' => array(
                    'route' => '/api/group[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\Group',
                    ),
                ),
            ),
            
            'api-group-chat' => array(
                'type' => 'Segment' ,
                'options' => array(
                    'route' => '/api/group/chat[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\GroupChat',
                    ),
                ),
            ),
            
            'user-bday' => array(
                'type' => 'Segment' ,
                'options' => array(
                    'route' => '/api/users-bday[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\Birthday',
                    ),
                ),
            ),
            
            
        ),
    ),
    
    
    'view_manager' => array(
        'template_path_stack' => array(
            'product' => __DIR__ . '/../view',
        ),
        
        // for JSON file return
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        
    ),
    
    
);

