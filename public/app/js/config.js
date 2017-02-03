/**
 * Defines constants for application
 */
define(['angular'], function (angular) {
  return angular.module('app.constants', [])
    .constant('CONFIG', {
       
       ImageBasePath: baseUrl + '/uploads',
       ApiBaseUrl : baseUrl + '/api',
       BaseUrl: baseUrl + '/app',
       
       
    })
    .constant('AUTH_EVENTS', {
        Authenticated: 'auth-authenticated',
        LoginSuccess: 'auth-login-success',
        LoginFailed: 'auth-login-failed',
        LogoutSuccess: 'auth-logout-success',
        SessionTimeout: 'auth-session-timeout',
        NotAuthenticated: 'auth-not-authenticated',
        NotAuthorized: 'auth-not-authorized'
    })
    ;
   
});



