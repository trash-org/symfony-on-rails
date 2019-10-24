<?php

namespace App\Rails\Eloquent\Fixture\Command;

use App\Rails\Eloquent\Fixture\Service\FixtureService;
use Symfony\Component\Console\Command\Command;

abstract class BaseCommand extends Command
{

    protected $fixtureService;

    public function __construct(?string $name = null, FixtureService $fixtureService)
    {
        parent::__construct($name);
        $this->fixtureService = $fixtureService;
    }

}
