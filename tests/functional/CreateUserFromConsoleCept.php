<?php

$I = new FunctionalTester($scenario);
$I->wantTo('create a new user from the command line');

$I->runShellCommand('php artisan create:user superadmin@email.com "Super Admin" --admin');
$I->seeInShellOutput('User created');
$I->seeInDatabase('users', ['email' => 'superadmin@email.com', 'rights' => '["admin"]']);
