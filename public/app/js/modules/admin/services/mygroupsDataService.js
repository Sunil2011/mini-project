define(['./../module'], function (module) {
    
    "use strict";
    
    module.registerFactory('mygroupsDataService' , ['$http', '$q', '$log', 'CONFIG',
        function($http, $q, $log, CONFIG){
            return {
                getmyGroupList : getmyGroupList ,
                getGroupDetails : getGroupDetails  ,
                addGroup : addGroup ,
                getOtherGroupList : getOtherGroupList ,
                joinGroup : joinGroup,
                getGroupCommentList : getGroupCommentList,
                postGroupComment :postGroupComment,
                likeComment : likeComment,
                whoLikedComment : whoLikedComment
            };
            
            function getmyGroupList() {
               
                return $http.get(CONFIG.ApiBaseUrl + '/group/myGroups',
                {params: {} })
                .then(function successCallback(response) {
                    // console.log(response.data);
                    var data = response.data;
                    return data;
                }, function errorCallback(response) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
            }
            
            // get group members
            function getGroupDetails(groupId) {
                if (groupId) {
                    return $http.get(CONFIG.ApiBaseUrl + '/group/' + groupId,
                            {params: {}})
                            .then(function successCallback(response) {
                                var data = response.data;
                                return data;
                            }, function errorCallback(response) {
                                // called asynchronously if an error occurs
                                // or server returns response with an error status.
                            });
                }else{
                    alert('groupId is null') ;
                }

            }
            
            function addGroup(params){
                
                return $http({
                    url: CONFIG.ApiBaseUrl + '/group',
                    method: 'POST',
                    data: params,
                    headers: {'Content-Type': undefined},
                    transformRequest: function (params, headersGetter) {
                        var formData = new FormData();
                        angular.forEach(params, function (value, key) {
                            formData.append(key, value);
                        });
                        
                        var headers = headersGetter();
                        delete headers['Content-Type'];

                        return formData;
                    },
                }).then(function successCallback(response) {
                    var data = response.data;
                    return data;
                }, function errorCallback(response) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
            }
            
            function getOtherGroupList(){
                
                return $http.get(CONFIG.ApiBaseUrl + '/group/otherGroups',
                {params: {} })
                .then(function successCallback(response) {
                    // console.log(response.data);
                    var data = response.data;
                    return data;
                }, function errorCallback(response) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
                
            }
            
            function joinGroup(groupId){
                
                if (groupId) {
                    return $http.get(CONFIG.ApiBaseUrl + '/group/joinGroup/' + groupId,
                            {params: {}})
                            .then(function successCallback(response) {
                                var data = response.data;
                                return data;
                            }, function errorCallback(response) {
                                // called asynchronously if an error occurs
                                // or server returns response with an error status.
                            });
                }else{
                    alert('groupId is null') ;
                }
                
            }
            
            function getGroupCommentList(groupId){
                
                if (groupId) {
                    return $http.get(CONFIG.ApiBaseUrl + '/group/chat/getGroupChat/' + groupId,
                            {params: {}})
                            .then(function successCallback(response) {
                                var data = response.data;
                                return data;
                            }, function errorCallback(response) {
                                // called asynchronously if an error occurs
                                // or server returns response with an error status.
                            });
                }else{
                    alert('groupId is null') ;
                }
            }
            
            function postGroupComment(params){
                return $http({
                    url: CONFIG.ApiBaseUrl + '/group/chat/addGroupChat',
                    method: 'POST',
                    data : $.param(params),
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(response){
                  //console.log(response);       
                  var data = response.data ;
                  return data ;
                });
            }
            
            function likeComment(params){
                return $http({
                    url: CONFIG.ApiBaseUrl + '/group/chat/likeChat',
                    method: 'POST',
                    data : $.param(params),
                     headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function(response){                         
                  var data = response.data ;
                  return data ;
                });
            }
            
            function whoLikedComment(chatId){
                 if (chatId) {
                    return $http.get(CONFIG.ApiBaseUrl + '/group/chat/getLikedChatDetails/' + chatId,
                            {params: {}})
                            .then(function successCallback(response) {
                                var data = response.data;
                                return data;
                            }, function errorCallback(response) {
                                
                            });
                }else{
                    alert('chatId is null') ;
                }
            }
            
        }]);
});







