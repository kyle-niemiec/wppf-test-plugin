<?php

use Doctum\Doctum;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name( '*.php' )
    ->in( sprintf( '%s/includes', __DIR__ ) )
    ->in( sprintf( '%s/admin/includes', __DIR__ ) );

return new Doctum( $iterator, [
    'title' => 'WordPress Plugin Framework',
    'build_dir' => sprintf( '%s/docs/api', __DIR__ ),
    'cache_dir' => sprintf( '%s/.doctum-cache', __DIR__ ),
] );
