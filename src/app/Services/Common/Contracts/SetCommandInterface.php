<?php

namespace App\Services\Common\Contracts;

use Illuminate\Foundation\Console\ClosureCommand;

interface SetCommandInterface
{
    public function setCommand(ClosureCommand $closureCommand) : void;
}
