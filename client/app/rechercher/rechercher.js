'use strict';

angular.module('tp1App')
.config	(function ($routeProvider) {
    $routeProvider
      .when('/rechercher', {
        templateUrl: 'app/rechercher/rechercher.html',
        controller: 'RechercherCtrl'
      });
  }
  );