define(['angular',
  'angular-couch-potato',
  'angular-ui-router',
  'angular-bootstrap'
], function (angular, couchPotato, uiRouter, bootstrap) {

  "use strict";


  var module = angular.module('app.layout', ['ui.router', 'ui.bootstrap']);


  couchPotato.configureApp(module);

  module.config(['$stateProvider', '$couchPotatoProvider', '$urlRouterProvider' , function ($stateProvider, $couchPotatoProvider, $urlRouterProvider) {
      
      $stateProvider
      .state('app', {
        abstract: true,
        template: '<ui-view> </ui-view>',
       
      
      })
      .state('app.home', {
        url: '/',
        templateUrl: './js/modules/layout/views/test.html',
        controller: 'AppController',
        data : {
            authRequired : true,
        },
        resolve: {
            deps: $couchPotatoProvider.resolveDependencies([
               './js/modules/layout/controllers/appController'
            ])
        }
      });
     
      $urlRouterProvider.otherwise('/');
    }]);

  module.run(function ($couchPotato) {
    module.lazy = $couchPotato;
  });
  
  console.log("layout", module);
  return module;
});




