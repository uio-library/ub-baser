<?php

$I = new FunctionalTester($scenario);
$I->am('an administrator');
$I->wantTo('add a new user');

Auth::loginUsingId(1);

$I->amOnPage('/admin/users/create');
$I->submitForm('//form', [
    'name' => 'Tom',
    'email' => 'tom@email.com'
]);

$I->see('En epost er sendt');
$I->seeInDatabase('users', ['email' => 'tom@email.com']);

