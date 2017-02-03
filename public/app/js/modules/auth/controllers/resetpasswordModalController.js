define(['./../module'], function (module) {

    module.registerController('resetpasswordModalController', ['CONFIG',  '$rootScope', '$scope', '$state', '$location', '$timeout', 'notify', '$uibModalInstance', 'resetpassService',
        function (CONFIG, $rootScope, $scope, $state, $location, $timeout, notify, $uibModalInstance, resetpassService) {
        
            vm = this;
                                
            vm.Reset = function ($valid) {
                
                if (!$valid){
                    notify.error("reset-password", "provide all input");
                    return;                    
                }
                
                var data = {
                 'oldpassword' : vm.oldpassword ,
                 'newpassword' : vm.newpassword
                }; 
              
                resetpassService.resetpass(data)
                   .then(function(response){
                      if (response.success) {
                        vm.close();
                        notify.success("reset-password", response.message);
                    } else {
                        notify.error("reset-password", response.message);
                        return ;
                    }
                });
              
            };
            
            vm.close = function () {
            $uibModalInstance.close();
            }; 
                
            
     }]);

});

