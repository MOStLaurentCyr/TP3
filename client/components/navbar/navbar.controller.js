'use strict';

angular.module('tp1App')
  .controller('NavbarCtrl', function ($scope, $location) {
    $scope.menu = [
    {'title': 'Home','link': '/'},
    {'title':'Accueil','link':'accueil'},
    {'title':'Rechercher','link':'rechercher'},
    {'title':'Register','link':'register'},
    {'title':'Contact','link':'contact'},
    {'title':'Login','link':'login'},
    {'title':'Liste de favoris','link':'favs'}];

    $scope.isCollapsed = true;

    $scope.isActive = function(route) {
      return route === $location.path();
    };
  });