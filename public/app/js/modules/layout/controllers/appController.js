define(['./../module'], function (module) {

  module.registerController('AppController', ['$scope', '$location', '$timeout', function ($scope, $location, $timeout) {
      $scope.text = "This is test page" ;
    }]);

});

