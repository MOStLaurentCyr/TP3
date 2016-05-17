'use strict';

angular.module('tp1App')
  .controller('ContactCtrl', function ($scope, $http) {
	$scope.sendFeedback = function(){ 
		console.log ('bonjour');
 		var apiUrl = 'https://crispesh.herokuapp.com/api';
    $http({
        method: 'POST',
        url: apiUrl + '/contact',
        data: {email: $scope.email, name: $scope.username, reason: $scope.subject, body: $scope.message},
        
        }).then(
        function successCallback(data){
            console.log(data);
        },
        function successCallback(error){
            console.log("Erreur", error);
       });
    
}});
