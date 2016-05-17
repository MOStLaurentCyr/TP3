'use strict';

angular.module('tp1App')
  .config(function ($routeProvider) {
    $routeProvider
      .when('/accueil', {
        templateUrl: 'app/accueil/accueil.html',
        controller: 'AccueilCtrl'
      });
  });
