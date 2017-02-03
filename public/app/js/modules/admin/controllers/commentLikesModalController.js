define(['./../module'], function (module) {

    module.registerController('commentLikesModalController', ['CONFIG', '$uibModalInstance', '$scope', '$state', 'chatId', 'mygroupsDataService',
        function (CONFIG, $uibModalInstance, $scope, $state, chatId, mygroupsDataService) {
           
            var vm = this;                                    
            vm.chat = {} ;            
            vm.chat.id = chatId || '';
            
            
            if(vm.chat.id){
              
                mygroupsDataService.whoLikedComment(vm.chat.id)
                    .then(function(response){
                        if(response.success){
                           // console.log(response.users_liked);
                            vm.usersLiked = response.users_liked ;
                        }else{
                            alert(response.message);
                        }   
                });
                
            };

            
            vm.close = function () {
                $uibModalInstance.close();
            };
            
            

        }]);
});








