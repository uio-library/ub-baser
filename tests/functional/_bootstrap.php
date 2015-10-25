<?php
// Here you can initialize variables that will be available to your tests

App\User::create([
    'email' => 'admin@email.com',
    'name' => 'Mr. Admin User',
    'rights' => ['admin']
]);
