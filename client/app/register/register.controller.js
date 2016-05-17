'use strict';



  angular.module('tp1App')
  .controller('RegisterCtrl', function ($scope, $http)
    { 
	$scope.register = function(){ 
		console.log ('bonjour');
 		var apiUrl = 'https://crispesh.herokuapp.com/api';
    $http({
        method: 'POST',
        url: apiUrl + '/register',
        data: {email: $scope.courrielUtilisateur, password: $scope.password, firstname: $scope.prenomUtilisateur, lastname: $scope.nomUtilisateur},
        
        }).then(
        function successCallback(data){
            console.log(data);
        },
        function successCallback(error){
            console.log("Erreur", error);
       });
}});