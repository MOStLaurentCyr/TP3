'use strict';

describe('Controller: RegisterCtrl', function () {


  it('should display a notification if the email is not valid', function(){
    element(by.id('emailInvalid')).isDisplayed().then(function(visible){
      expect(visible).toBeFalsy();
    });
    element(by.id('courrielUtilisateur')).sendKeys('invalidEmail');

    element(by.id('emailInvalid')).isDisplayed().then(function(visible){
      expect(visible).toBeTruthy();
    });
  });


//nouveau test



  it('should display a notification if the form is submitted with success', function(){
    element(by.id('messageReussite')).isDisplayed().then(function(visible){
      expect(visible).toBeFalsy();
    });

    element(by.id('prenomUtilisateur')).sendKeys('test');
    element(by.id('nomUtilisateur')).sendKeys('test');
    element(by.id('courrielUtilisateur')).sendKeys('test@test.com');
    element(by.id('password1')).sendKeys('test');
    element(by.id('password2')).sendKeys('test');
    element(by.id('confirmButton')).click();

    element(by.id('messageReussite')).isDisplayed().then(function(visible){
      expect(visible).toBeTruthy();
    });
  });

  it('should display a notification if the two password are not identical', function(){
      element(by.id('messageErreurPassword')).isDisplayed().then(function(visible){
      expect(visible).toBeFalsy();
    });
    element(by.id('password1')).sendKeys('test');
    element(by.id('password2')).sendKeys('test1');

    element(by.id('messageErreurPassword')).isDisplayed().then(function(visible){
      expect(visible).toBeTruthy();
    });    
  });




// fin nouveau test




});



