
'use strict';

define([
  'angular',
  'angular-couch-potato',
  'angular-ui-router',
  'pnotify.main',
  'angular-bootstrap',
  'jquery.ui.widget'
  
], function (angular, couchPotato) {

  console.log("app js loaded");
  var app = angular.module('app', [
    
    'scs.couch-potato',
    
    'ui.router',
    'ui.bootstrap',
    'app.constants',
    
    //modules names 
    
    'app.admin',
    'app.layout',
    'app.auth'
    
    
  ]);
  
  
  app.controller("BaseController", ['$filter', '$rootScope', '$scope', '$state', '$timeout', 'CONFIG','AuthService', 'AUTH_EVENTS', 'SessionService','notify','$uibModal',
    function ($filter, $rootScope, $scope, $state, $timeout, CONFIG, AuthService, AUTH_EVENTS, SessionService, notify, $uibModal) {
        var vm = this;
        $scope.headerView = "./js/modules/layout/views/header.html";
        $scope.sidebarView = "./js/modules/layout/views/sidebar.html";
        
        $scope.imageBasePath = CONFIG.ImageBasePath;
        $scope.focusedForm = true;
        $scope.layoutLoading = false;
        $scope.sidebarCollapsed = false;

     // layout methods
        $scope.refresh = function () {
            console.log('refresh') ; 
            $state.reload();
            
        };
        
        // auth event handling
        function updateSession(event, toState) {
          $scope.focusedForm = !AuthService.isAuthenticated();
          $scope.layoutLoading = AuthService.isAuthenticated();
          $rootScope.userSession = SessionService;
        }
        
        //toggle side-bar
        $scope.toggleLeftBar = function () {
          $scope.sidebarCollapsed = !$scope.sidebarCollapsed;
        };
        
        function getUserRootPage() {
            var homeRoute = 'app.mygroups';
            return homeRoute;
        }
        
        
        $rootScope.$on(AUTH_EVENTS.NotAuthorized, function () {
          notify.error('Auth', "You are not authorized to access!");
          $state.go('app.logout');
        });
        
        $rootScope.$on(AUTH_EVENTS.LoginFailed, function () {
          notify.error('Auth', "Username-password not matched");
          return ;
        });

        $rootScope.$on(AUTH_EVENTS.LoginSuccess, function (event, toState) {
          updateSession();
          $state.go(getUserRootPage());
        });

        $rootScope.$on(AUTH_EVENTS.Authenticated, function (event, toState) {
          updateSession();
          var homeRoute = getUserRootPage();
          var route = $rootScope.previousState && $rootScope.previousState.name ? $rootScope.previousState.name : homeRoute;
          var routeParams = (route !== homeRoute) ? $rootScope.previousStateParams : {};
          $state.go(route, routeParams);
        });

        $rootScope.$on(AUTH_EVENTS.NotAuthenticated, function (event, toState) {
          $state.go('app.login');
        });
        
        
            // reset password
        $scope.resetPass = function () {
            var modalInstance = $uibModal.open({
               animation: $scope.animationsEnabled,
               templateUrl: './js/modules/auth/views/reset-password-modal.html',
               controller: 'resetpasswordModalController',
               controllerAs: 'resetpsd',
               size: 'lg',
               backdrop: 'static'
           
            });

            modalInstance.result.finally(function () {
                    $state.reload();
            });
        
        };
    
        
      
    }]);


  app.run(['$rootScope', '$location', '$state', 'CONFIG', 'AUTH_EVENTS', 'AuthService', 'SessionService', 'SessionProvider',
    function ($rootScope,  $location, $state, CONFIG, AUTH_EVENTS, AuthService, SessionService, SessionProvider) {
      

      $rootScope.$on('$stateChangeStart', function (event, toState, toParams) {
        var isAuthRequired = toState.data.authRequired;
       //  console.log(SessionService.id);
        
        if(isAuthRequired && !AuthService.isAuthenticated()){
            // user isn't logged in
            // console.log(!AuthService.isAuthenticated()) ;
            event.preventDefault();
            $rootScope.previousState = toState;
            $rootScope.previousStateParams = toParams;
            $rootScope.$broadcast(AUTH_EVENTS.NotAuthenticated);
            //$state.go('app.login');
            return;
            
        } else {
            return ;
        }
                
        if (toState.name === 'app.login' && AuthService.isAuthenticated()) {
           event.preventDefault();          
           $state.go('app.home');
        }
        
      });

    }
  ]);
  
  
  return app;
}) ;


