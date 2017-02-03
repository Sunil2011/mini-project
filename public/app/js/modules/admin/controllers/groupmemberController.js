/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


define(['./../module'], function (module) {
    
    module.registerController('groupmemberController', [ '$scope', '$state', '$stateParams', 'mygroupsDataService','CONFIG',
        function ( $scope, $state, $stateParams , mygroupsDataService , CONFIG) {
            
            var vm = this;
            vm.ImagePath = CONFIG.ImageBasePath ;
            
            //vm.group = {};
            vm.group_id = $stateParams.group_id || '';
            //alert(vm.group_id);
            if(vm.group_id){
                mygroupsDataService.getGroupDetails(vm.group_id)
                    .then(function(response){
                        if(response.success){
                           // console.log('group_members', response.group.group_members);
                            vm.group_members = response.group.group_members; 
                            vm.group_name = response.group.group_name ;
                            vm.group_image = response.group.image ;
                            
                        }else{
                            console.log('success: false');
                        }
                       
                });
            }
               

        }]);

});









