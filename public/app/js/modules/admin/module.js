define(['angular',
    'angular-couch-potato',
    'angular-bootstrap',
    'angular-ui-router'], function (ng, couchPotato) {

    "use strict";


    var module = ng.module('app.admin', ['ui.router', 'ui.bootstrap']);

    couchPotato.configureApp(module);

    module.config(['$stateProvider', '$couchPotatoProvider', '$urlRouterProvider', function ($stateProvider, $couchPotatoProvider, $urlRouterProvider) {
            $stateProvider
                .state('app.mygroups', {
                    url: '/mygroups',
                    
                    templateUrl: './js/modules/admin/views/mygroups.html',
                    controller: 'mygroupsController',
                    controllerAs:'myGps',
                    
                    resolve: {
                        deps: $couchPotatoProvider.resolveDependencies([
                            './js/modules/admin/services/mygroupsDataService' ,
                             
                            './js/modules/admin/controllers/mygroupsController'
                        ])
                    },
                    data: {
                      authRequired : true
                        }
                })
                
               .state('app.addgroup', {
                    url: '/addgroup',
                    
                    templateUrl: './js/modules/admin/views/addgroup.html',
                    controller: 'addgroupController',
                    controllerAs:'addGp',
                    
                    resolve: {
                        deps: $couchPotatoProvider.resolveDependencies([
                            './js/modules/admin/services/mygroupsDataService' ,
                            './js/modules/layout/directives/fileModel',
                            './js/modules/admin/controllers/addgroupController'
                        ])
                    },
                    data: {
                      authRequired : true
                        }
                })
                
                .state('app.group', {
                    url: '/group/{group_id}',
                    
                    templateUrl: './js/modules/admin/views/group.html',
                    controller: 'groupController',
                    controllerAs:'Grp',
                    
                    params :{ 
                        
                    },
                    resolve: {
                        deps: $couchPotatoProvider.resolveDependencies([
                            './js/modules/admin/services/mygroupsDataService' ,
                            './js/modules/layout/directives/schrollBottom',                            
                            './js/modules/admin/controllers/groupController',
                            './js/modules/admin/controllers/commentLikesModalController'
                        ])
                    },
                    
                    data: {
                      authRequired : true
                    }
                })
                   
                
                .state('app.groupmember', {
                    url: '/groupmembers/{group_id}',
                    
                    templateUrl: './js/modules/admin/views/groupmember.html',
                    controller: 'groupmemberController',
                    controllerAs:'gpM',
                    
                    params :{ 
                        
                    },
                    resolve: {
                        deps: $couchPotatoProvider.resolveDependencies([
                            './js/modules/admin/services/mygroupsDataService' ,
                            
                            './js/modules/admin/controllers/groupmemberController'
                        ])
                    },
                    
                    data: {
                      authRequired : true
                    }
                })
                                                                                  
                               
                .state('app.othergroups', {
                    url: '/othergroups',
                    
                    templateUrl: './js/modules/admin/views/othergroups.html',
                    controller: 'othergroupsController',
                    controllerAs:'oGp',
                    
                    resolve: {
                        deps: $couchPotatoProvider.resolveDependencies([
                            './js/modules/admin/services/mygroupsDataService' ,                           
                            './js/modules/admin/controllers/othergroupsController'
                        ])
                    },
                   
                    data: {
                      authRequired : true
                    }
                })
                
                .state('app.upcomingbday', {
                    url: '/upcoming-bday',
                    
                    templateUrl: './js/modules/admin/views/upcomingbday.html',
                    controller: 'upcomingbdayController',
                    controllerAs:'upcomingbday',
                    
                    resolve: {
                        deps: $couchPotatoProvider.resolveDependencies([
                            './js/modules/admin/services/bdayDataService' ,                            
                            './js/modules/admin/controllers/upcomingbdayController'
                        ])
                    },
                   
                    data: {
                      authRequired : true
                    }
                })
                
                .state('app.badylist', {
                    url: '/birthday-list',
                    
                    templateUrl: './js/modules/admin/views/bdayList.html',
                    controller: 'bdayController',
                    controllerAs:'bday',
                    
                    resolve: {
                        deps: $couchPotatoProvider.resolveDependencies([
                            './js/modules/admin/services/bdayDataService' ,                            
                            './js/modules/admin/controllers/bdayController'
                        ])
                    },
                   
                    data: {
                      authRequired : true
                    }
                })
            
               ; 
                
        }]);
    
    module.run(function ($couchPotato) {
        module.lazy = $couchPotato;
    });
    
    //console.log('1234');
    return module;
});






