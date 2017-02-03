/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define(['./../module'], function (module) {

    module.registerController('addgroupController',
    [ '$scope', '$state', 'mygroupsDataService', '$stateParams','CONFIG','notify',
        function ( $scope, $state, mygroupsDataService, $stateParams , CONFIG, notify ) {
            var vm = this;
            vm.ImagePath = CONFIG.ImageBasePath ;
            

            vm.cancel = function () {
                $state.go('app.mygroups');
            };

            // valid argumnet is for form validation 
            vm.submitGroup = function (valid ) {
                        
               if (valid == false ) {
                  // $state.reload() ;
                    return ;
                }
                        
                var data = { 
                    'group_name': vm.group_name,
                };
                
                if ($scope.file != undefined) {
                    data.file = $scope.file;
                }
                console.log('group-submit :-',data);
                
                mygroupsDataService.addGroup(data)
                .then(function (response) {
                   // console.log(response);
                    if (response.success) { 
                        $state.go('app.mygroups');
                        notify.success("Group" , "new-group created successfully !");
                        
                    } else {
                        alert(response.message);
                    }
                });
            };
            
  
            
        }]);
});


    




    


