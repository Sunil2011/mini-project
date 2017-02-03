/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


define(['./../module'], function (module) {
    
    module.registerController('othergroupsController', [ '$scope', '$state', '$stateParams', 'mygroupsDataService','CONFIG','notify',
        function ( $scope, $state, $stateParams , mygroupsDataService , CONFIG, notify ) {
            
            var vm = this;
            vm.ImagePath = CONFIG.ImageBasePath ;
            
            mygroupsDataService.getOtherGroupList()
                .then(function(response) {
                  //  console.log(response.success);
                    
                    if(response.success) {
                       vm.othergroups = response.other_groups;
                   
                    } else {
                    console.log('success:false');
                    }
            });
            
            $scope.joinNewGroup= function(groupId){
                
                mygroupsDataService.joinGroup(groupId)
                   .then(function(response){
                       
                       if (response.success) {
                           $state.go('app.mygroups');
                           notify.success("Group", "you have joined a new group ! ");
                           
                       } else {
                           console.log('success:false');
                       }
                       
                });
            };
            

    }]);

});









