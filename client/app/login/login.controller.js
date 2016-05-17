 angular.module('tp1App')
 .controller('LoginCtrl', function($scope, $http){
$scope.login = function(){
      var apiUrl = 'https://crispesh.herokuapp.com/api';

         $http({
             method: 'POST',
             url: apiUrl + '/login_check',
             data: {username: $scope.username, password: $scope.password},
             }).then(
         function successCallback(data){
             localStorage.setItem('JWT', data.data.token);
             $scope.succesMessage="Vous etes connecte"
             $scope.isVisible = true;
             console.log($scope.isVisible);
             console.log(succesMessage);
         },
         function errorCallback(error){
             
         });
    }

$scope.logout = function(){
    localStorage.removeItem('JWT');
    $scope.isVisible = false;
}



  });
