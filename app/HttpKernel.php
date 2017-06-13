<?php

namespace App;

use Illuminate\Foundation\Http\Kernel as BaseHttpKernel;

class HttpKernel extends BaseHttpKernel
{
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'waze' => [],
    ];

}
