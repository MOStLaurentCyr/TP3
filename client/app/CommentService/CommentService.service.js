'use strict';


var myApp = angular.module('tp1App');

myApp.service('Comment', function($resource)
{
	var restAPIUrl = 'https://crispesh.herokuapp.com/api';
	return $resource(restAPIUrl + '/comments/:id', {id:'@id'},{forMovie:{method:'GET', isArray: true, url:restAPIUrl + '/comments?movie_id=:id'}},{update: {method:'PUT'}});
});