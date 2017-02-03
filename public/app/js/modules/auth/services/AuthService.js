define(['./../module'], function (module) {

  module.factory('AuthService', ['CONFIG', 'AUTH_EVENTS', 'SessionService', '$rootScope', '$http', '$q', '$log', '$httpParamSerializer', AuthService]);

  function AuthService(CONFIG, AUTH_EVENTS, SessionService, $rootScope, $http, $q, $log, $httpParamSerializer) {

    return {
      login: login,
      logout: logout,
      getUser: getUser,
      registerUser :  registerUser ,
      isAuthenticated: isAuthenticated,
      isAuthorized: isAuthorized
    };

    function login(params) {
        //console.log(params);
      return $http({
        url: CONFIG.ApiBaseUrl + '/auth/login',
        method: 'POST',
        data: $httpParamSerializer(params),
        headers: {
          'Content-type': 'application/x-www-form-urlencoded; charset=utf-8' // Note the appropriate header
        }
      }).then(function (response) {
        var data = response.data;
        if (data.id) {
          SessionService.create(data.id, data.account);
          $rootScope.$broadcast(AUTH_EVENTS.LoginSuccess);
        } else {
          SessionService.destroy();
          //console.log("login-failed");
          $rootScope.$broadcast(AUTH_EVENTS.LoginFailed);
        }
        return data;
      });
    }

    function logout() {
      return $http({
        url: CONFIG.ApiBaseUrl + '/auth/logout',
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      }).then(function (response) {
        $rootScope.$broadcast(AUTH_EVENTS.LogoutSuccess);
        var data = response.data;
        return data;
      });
    }

    function getUser() {
      return $http({
        url: CONFIG.ApiBaseUrl + '/auth/me',
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      }).then(function (response) {
        var data = response.data;
        return data;
      });
    }
    
    function registerUser(params){
        return $http({
            url: CONFIG.ApiBaseUrl + '/user',
            method: 'POST',
            data : $.param(params),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function(response){
             var data = response.data ;
             return data ;
        });
        
    }
    
    
    function isAuthenticated() {
      return !!SessionService.id;
    };

    function isAuthorized() {
      if (!isAuthenticated()) {
        return false;
      }
      
      return true;
    };    
   
  }
});
