'use strict';

angular.module('tp1App')
  .controller('RechercherCtrl', function ($scope) {
    $scope.message = 'Hello';
  });

  angular.module('tp1App')
  .controller('RechercherCtrl', function ($scope, $http)
    { 


      $scope.recherche = function(){
       $http.get('http://omdbapi.com/?', {params : {s : $scope.search }}).then(
       function successCallback(response) {
         $scope.errorMsg = '';
         $scope.movies = response.data.Search; 
    }, function errorCallback(response) {
         $scope.errorMsg = 'Une ereur s\'est produite';
  })};
});