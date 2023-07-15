<?php

namespace Alphaolomi\LaravelApprovals\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Alphaolomi\LaravelApprovals\Approvals
 */
class Approvals extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Alphaolomi\LaravelApprovals\Approvals::class;
    }
}
