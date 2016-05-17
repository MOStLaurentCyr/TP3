'use strict';

describe('Controller: AccueilCtrl', function () {

  beforeEach(inject(function($httpBackend){
    httpBackend = $httpBackend;
  }));

  var AccueilCtrl, scope, httpBackend;


  it('should contain a query value', function () {
    var babaResponse = {"Search":[{"Title":"Ali Baba and the Seven Dwarfs","Year":"2015","imdbID":"tt4728338","Type":"movie","Poster":"http://ia.media-imdb.com/images/M/MV5BMjAwNTQyMjA0NF5BMl5BanBnXkFtZTgwNjUyODMyNzE@._V1_SX300.jpg"},{"Title":"Süper Baba","Year":"1993–1997","imdbID":"tt0287883","Type":"series","Poster":"N/A"},{"Title":"Ali Baba Bunny","Year":"1957","imdbID":"tt0050111","Type":"movie","Poster":"http://ia.media-imdb.com/images/M/MV5BMTM1NjM4MDE1OV5BMl5BanBnXkFtZTcwMDU2MjE2MQ@@._V1_SX300.jpg"},{"Title":"Baba","Year":"2002","imdbID":"tt0326746","Type":"movie","Poster":"N/A"},{"Title":"Joi Baba Felunath: The Elephant God","Year":"1979","imdbID":"tt0077775","Type":"movie","Poster":"N/A"},{"Title":"Ali Baba and the Forty Thieves","Year":"1944","imdbID":"tt0036591","Type":"movie","Poster":"http://ia.media-imdb.com/images/M/MV5BMTQxMDkzNjIyNV5BMl5BanBnXkFtZTcwNDAxOTgxMQ@@._V1_SX300.jpg"},{"Title":"Ali Baba and the Forty Thieves","Year":"1971","imdbID":"tt0185853","Type":"movie","Poster":"N/A"},{"Title":"Ali Baba and the Forty Thieves","Year":"1954","imdbID":"tt0046695","Type":"movie","Poster":"http://ia.media-imdb.com/images/M/MV5BNDY1ODQ4ODc3Ml5BMl5BanBnXkFtZTcwMzIyODI5Ng@@._V1_SX300.jpg"},{"Title":"Selo gori, a baba se ceslja","Year":"2007–","imdbID":"tt0906069","Type":"series","Poster":"N/A"},{"Title":"Zhila-byla odna baba","Year":"2011","imdbID":"tt2065015","Type":"movie","Poster":"N/A"}],"totalResults":"204","Response":"True"}

    httpBackend.when('GET', 'http://www.omdbapi.com/?&s=baba').respond(200, babaResponse);

    httpBackend.expect('GET', 'http://www.omdbapi.com/?&s=baba');

   scope.query = 'baba';
   scope.recherche();
   httpBackend.flush();
  });

  
});
