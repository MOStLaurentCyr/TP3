angular.module('tp1App')
   .controller('AccueilCtrl', function ($scope, $http, Comment)
{
$http.get('http://omdbapi.com/?', {params : {s : 'twenty', y: '2016'}}).then(
	function successCallback(response) {
		$scope.films = response.data.Search;
  
  }, function errorCallback(response) {
  	$scope.errorMessage = "Le serveur ne repond pas"
  });

	$scope.showComments = function(movieId){
		$scope.comments = Comment.forMovie({id: movieId}),
		function errorCallback(response){
			$scope.commentError = "Doit etre connecte pour voir les commentaire";
		}};
	
	$scope.deleteComment = function(commentId, movieId){
		Comment.delete({id: commentId},
		function successCallback(response){
			$scope.showComments(movieId);
		});
	};

	$scope.modifyComment = function(commentBody, commentId){
		var modifiedComment = Comment.get({id: commentId}, function(){
		modifiedComment.body = commentBody;
		Comment.delete({id: commentId});		
		$scope.addComment(modifiedComment.movie_id,modifiedComment.body);
	});

	};

	$scope.addComment = function(movieId, commentBody){
		var status = 1;
		Comment.save({movie_id: movieId, status: status, body: commentBody},
			function successCallback(response){
				$scope.showComments(movieId);
			});
		};

	$scope.getComment = function(commentId){
		$scope.specificComment = Comment.get({id: commentId})
	};
});



 