/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define(['./../module'], function (module) {
   
    module.registerController('upcomingbdayController', [ '$scope', '$state', '$stateParams', 'bdayDataService','CONFIG',
        function ( $scope, $state, $stateParams,bdayDataService, CONFIG) {
            
            var vm = this;
            vm.ImagePath = CONFIG.ImageBasePath;


            bdayDataService.getUpcomingBday()
                .then(function (response) {
                    if (response.success) {
                        vm.bdays = response.bdays ;
                       // console.log(response.bdays);
                    } else {
                        console.log('success: false');
                    }

            });
          
            $scope.showAllBday = function(){
                $state.go('app.badylist');
                
            } ;

        }]);
    
});
