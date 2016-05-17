'use strict';

angular.module('tp1App', [
  'ngCookies',
  'ngResource',
  'ngSanitize',
  'ngRoute',
  'ui.bootstrap',
  'angular-jwt'
])
  .config(function ($routeProvider, $locationProvider) {
    $routeProvider
      .otherwise({
        redirectTo: '/'
      });

    $locationProvider.html5Mode(true);
  });


  angular.module('tp1App').config(function ($locationProvider, $httpProvider, jwtInterceptorProvider)
{
 
    jwtInterceptorProvider.tokenGetter = function(config, jwtHelper) {
 
    // Do not use token to get .html templates
    if (config.url.substr(config.url.length - 5) === '.html' ||
        config.url.indexOf('/api/') === -1
      )
    {
      return null;
    }
 
    var jwt = localStorage.getItem('JWT');
    if(jwt === null)
    {
      return null;
    }
 
    if (jwtHelper.isTokenExpired(jwt))
    {
      console.log("Token Expired !", jwtHelper.getTokenExpirationDate(jwt));
    }
    else
    {
      console.log("Token not expired", jwtHelper.getTokenExpirationDate(jwt));
      return jwt;
    }
  };
 
  $httpProvider.interceptors.push('jwtInterceptor');
 
}) ;
  