<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->laravel();
arch()->preset()->strict();
arch()->preset()->security()->ignoring([
    'assert',
]);

arch()
    ->expect('App')
    ->toUseStrictTypes()
    ->classes()
    ->not->toUse(['die', 'dd', 'dump']);

arch('controllers')
    ->expect('App\Http\Controllers')
    ->not->toBeUsed();

arch()
    ->expect('App\Http')
    ->toOnlyBeUsedIn('App\Http');

arch()
    ->expect('App\Services')
    ->toBeFinal()
    ->toBeClasses()
    ->not->toBeTraits()
    ->not->toBeInterfaces();

arch()
    ->expect('App\Actions')
    ->toBeClasses()
    ->toBeReadonly()
    ->toBeFinal()
    ->not->toBeTraits()
    ->not->toBeInterfaces();

arch()
    ->expect('App\Models')
    ->classes()
    ->toBeFinal();
