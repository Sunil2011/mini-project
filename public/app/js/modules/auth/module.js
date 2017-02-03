define(['angular',
  'angular-couch-potato',
  'angular-ui-router'], function (ng, couchPotato) {

  "use strict";


  var module = ng.module('app.auth', ['ui.router']);


  couchPotato.configureApp(module);

  module.config(['$stateProvider', '$couchPotatoProvider', '$urlRouterProvider', function ($stateProvider, $couchPotatoProvider, $urlRouterProvider) {
    $stateProvider
     .state('app.login', {
        url: '/login',
        templateUrl: './js/modules/auth/views/login.html',
        controller: 'AuthController',
        resolve: {
            deps: $couchPotatoProvider.resolveDependencies([
               './js/modules/auth/controllers/AuthController'
            ])
        },
        data: {
          authRequired : false
        }
      })
      
      .state('app.logout', {
        url: '/logout',
        controller: 'AuthController',
        resolve: {
            deps: $couchPotatoProvider.resolveDependencies([
               './js/modules/auth/controllers/AuthController'
            ])
        },
        data: {
          authRequired : true
        }
      })
      
      .state('app.signup', {
        url: '/sign-up',
        templateUrl: './js/modules/auth/views/sign-up.html',
        controller: 'AuthController',
        controllerAs : 'Auth' ,
        resolve: {
            deps: $couchPotatoProvider.resolveDependencies([
               './js/modules/auth/controllers/AuthController'
            ])
        },
        data: {
          authRequired : false
        }
      });
      
    }]);

  module.run(function ($couchPotato) {
    module.lazy = $couchPotato;
  });
  
  console.log("auth", module);
  return module;
});

