/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


define(['./../module'], function (module) {
    
  module.registerController('mygroupsController', [ '$scope', '$state', '$stateParams', 'mygroupsDataService','CONFIG',
        function ( $scope, $state, $stateParams , mygroupsDataService , CONFIG) {
            
            var vm = this;
            vm.ImagePath = CONFIG.ImageBasePath ;
            
            mygroupsDataService.getmyGroupList()
                .then(function(response) {
                  //  console.log(response.success);
                    
                if(response.success) {
                    
                    console.log("mygroups:",response.mygroups);
                    vm.mygroups = response.mygroups;
                   
                } else {
                    console.log('success:false');
                }
            });
            
            $scope.addGroup= function(){
                
                $state.go('app.addgroup');  
            };
            
            $scope.groupDetails = function( groupId){
                
                $state.go('app.group', {group_id: groupId });
                
            };
            
          //  $('#test').html("<b> hey ...</b>");

        }]);

});







