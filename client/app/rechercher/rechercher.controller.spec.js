'use strict';

describe('Controller: RechercherCtrl', function () {

  // load the controller's module
  beforeEach(module('tp1App'));

  var RechercherCtrl, scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    RechercherCtrl = $controller('RechercherCtrl', {
      $scope: scope
    });
  }));

  it('should ...', function () {
    expect(1).toEqual(1);
  });
});
