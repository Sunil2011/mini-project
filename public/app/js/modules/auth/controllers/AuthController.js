define(['./../module'], function (module) {

  module.registerController('AuthController', [ 'AUTH_EVENTS', 'CONFIG','SessionService', 'AuthService', '$rootScope', '$scope', '$state', '$location', '$timeout','notify', function ( AUTH_EVENTS, CONFIG, SessionService, AuthService, $rootScope, $scope, $state, $location, $timeout, notify ) {
    vm = this ;
    $scope.credentials = {
        username: '',
        password: ''
    };
      
    $scope.login =  function () {
          
        AuthService
        .login($scope.credentials)
        .then(function (response) {
          if (response.id) {
          //  $state.go('app.mygroups');  // redirecting to home page based on braodcasting in authService
          } else {
            $scope.credentials.password =  '';
           // notify.error("Auth", response.message); //not showing particular username/password error
          }
        });
    };
      
    $scope.logout =  function () {
        AuthService
        .logout()
        .then(function (response) {
          window.location.href = CONFIG.BaseUrl + $state.href('app.login');
        })
        .catch(function (response) {
          window.location.href = CONFIG.BaseUrl + $state.href('app.login');
        });
    };
      
      /*
      //not in use  we are calling app.signup using href in login.html      
      $scope.registerNewUser = function(){
          $state.go('app.signup');
      };
      */
    vm.register = function($valid){
        
        if (!$valid) {
            notify.error("Registration","please fill the required fields !");
            return ;
        }
          
        var data = { 
            'id': vm.user_id ,
            'username': vm.user_name,
            'email': vm.user_email,
            'password' : vm.user_password ,
            'dob': formatDate(vm.user_dob)
        };
          
        AuthService.registerUser(data)
          .then(function(response){
              //console.log(response);
              if (response.success) {
                   $state.go('app.login');
                   notify.success("register new user", "new user created successfully !");
              } else {
                  notify.error("register new user", response.message);
                  return ;
              }
              
          });
          
    };
      
    vm.cancel = function(){
        $state.go('app.login') ;
    } ;
      
    if ($state.current.name === 'app.logout') {
        $scope.logout();
    }
    
         
    //---- for datepicker  (register new user dob) start ..
    $scope.open = function() {
       $scope.popupOpened = true;
    };
   // $scope.format = 'yyyy-mm-dd';
    $scope.today = function() {         
        vm.user_dob = new Date();
    };
       
    $scope.clear = function() {
       vm.user_dob = null;
    };
      
    function formatDate(date) {
       var d = new Date(date),
        month = '' + (d.getMonth() + 1), // .getMonth() will give the month index
        dt = '' + d.getDate(),
        year = d.getFullYear();
        
        //converting to format yyyy-mm-dd (for jan-sept & 1-9 to 00 format ) 
        if (month.length < 2) {
           month = '0' + month;
        }
        if (dt.length < 2) {
           dt = '0' + dt;
        }
       
        return [year, month, dt].join('-');
    }  
    //---- for datepicker end ..        

    }]);
    

});