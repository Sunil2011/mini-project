define(['./../module'], function (module) {
   
    module.factory('resetpassService', ['CONFIG', '$rootScope', '$http', '$q', '$log', '$httpParamSerializer', resetpassServc]);
    
    function resetpassServc (CONFIG, $rootScope, $http, $q, $log, $httpParamSerializer) {
        
        return {
            resetpass : resetpass
        };
       
        function resetpass(params){
            return $http({
               url: CONFIG.ApiBaseUrl + '/user/resetPassword',
               method: 'POST',
               data: $httpParamSerializer(params),
               headers: {
                'Content-type': 'application/x-www-form-urlencoded; charset=utf-8' // Note the appropriate header
               }
           }).then(function(response){
                var data = response.data ;
                return data ;
           }); 
        }
    }
    
});


