
define(['./../module'], function (module) {
   
    module.registerController('bdayController', [ '$scope', '$state', '$stateParams', 'bdayDataService','CONFIG',
        function ( $scope, $state, $stateParams,bdayDataService, CONFIG) {
            
            var vm = this;
            vm.ImagePath = CONFIG.ImageBasePath;
            
            bdayDataService.getAllBdayList()
                .then(function (response) {
                    if (response.success) {
                        vm.bdays = response.bdays ;
                        
                        console.log(response.bdays);
                    } else {
                        console.log('success: false');
                    }

            });
            
            
            
        }]);
    
});


