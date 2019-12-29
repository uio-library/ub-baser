<?php

// Redirect routes

Route::get('norsklitteraturkritikk', function() {
    return redirect()->action([\App\Bases\Litteraturkritikk\Controller::class, 'index']);
});

Route::get('norsk-litteraturkritikk', function() {
    return redirect()->action([\App\Bases\Litteraturkritikk\Controller::class, 'index']);
});

