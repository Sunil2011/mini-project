define(['./../module'], function (module) {
    
    "use strict";
    
     module.registerFactory('bdayDataService' , ['$http', '$q', '$log', 'CONFIG',
        function($http, $q, $log, CONFIG){
            return {
                getAllBdayList : getAllBdayList,
                getUpcomingBday : getUpcomingBday,
                getBdayByMonth : getBdayByMonth
                
            };
            
            function getAllBdayList() {
                return $http.get(CONFIG.ApiBaseUrl + '/users-bday',
                {params: {} })
                .then(function successCallback(response) {                    
                    var data = response.data;
                    return data;
                }, function errorCallback(response) {
                    
                });
                
            }
            
            function getUpcomingBday() {
                return $http.get(CONFIG.ApiBaseUrl + '/users-bday/in30days',
                {params: {} })
                .then(function successCallback(response) {                    
                    var data = response.data;
                    return data;
                }, function errorCallback(response) {
                    
                });
            }
            
            function getBdayByMonth(monthIndex) {
                return $http.get(CONFIG.ApiBaseUrl + '/users-bday/month/' + monthIndex,
                {params: {} })
                .then(function successCallback(response) {                    
                    var data = response.data;
                    return data;
                }, function errorCallback(response) {
                    
                });
            }
            
        }]);
});

