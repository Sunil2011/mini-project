/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define(['./../module'], function (module) {
    
  module.registerController('groupController', [ '$scope', '$state', '$stateParams', 'mygroupsDataService','CONFIG','notify','$uibModal',
        function ( $scope, $state, $stateParams , mygroupsDataService , CONFIG, notify, $uibModal) {
            
            var vm = this;
            vm.ImagePath = CONFIG.ImageBasePath ; 
            vm.group_id = $stateParams.group_id || '';
            
            mygroupsDataService.getGroupCommentList(vm.group_id)
                .then(function(response) {
                  //  console.log(response.success);                    
                if(response.success) {                                      
                    vm.group_chats = response.group_chats;
                    vm.group_name = response.group_name ;
                    vm.group_id = response.group_id ;
                   
                } else {
                    console.log('success:false');
                }
            });
            
            $scope.postComment = function(){                                
                var data = { 
                    'group_id': vm.group_id,
                    'comment' : vm.postcomnt 
                };
                /*
                if(valid == false ){
                    console.log('product-name can not be empty ');
                  // $state.reload() ;
                    return ;
                };
                */
                mygroupsDataService.postGroupComment(data)
                    .then(function(response){                        
                        if(response.success){
                            $state.reload();
                        }else{
                           console.log('comment not posted'); 
                        }
                        
                });
                
                
                
            };
            
            $scope.addGroup= function(){                
                $state.go('app.addgroup');  
            };
            
            $scope.showGroupMembers = function(groupId){
                //alert(groupId);
                $state.go('app.groupmember', {group_id: groupId });                
            };
            
            $scope.likePost = function(chatId){                
                var data = {
                    'chat_id':chatId 
                };
                mygroupsDataService.likeComment(data)
                    .then(function(response){
                        //console.log(response);
                        if (response.success) {                            
                           if (response.info.isLiked) {
                               $state.reload();
                               notify.success("like" , 'you liked a comment !');
                           } else {
                               $state.reload();
                               notify.success("unlike" , 'you unliked a comment !');
                           }         
                            
                        } else {
                            console.log('like failed');
                        }
                });
            };
            
            $scope.likeDetails = function(chatId){
                var chatId = chatId || 0 ;
               // alert(chatId);
                var modalInstance = $uibModal.open({
                    animation: $scope.animationsEnabled,
                    templateUrl: './js/modules/admin/views/comment-likes-modal.html',
                    controller: 'commentLikesModalController',
                    controllerAs: 'commentLikes',
                    size: 'lg',
                    backdrop: 'static',
                    resolve: {
                        chatId: function () {
                            return chatId;
                        }
                    }
                });
                   
                modalInstance.result.finally(function () {
                    //$state.reload();
                    return ;
                });
                
                
            };
            

        }]);

});









