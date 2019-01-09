<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\Console;

use Contentful\Management\Client;
use Contentful\Management\Console\Command\GenerateEntryClassesCommand;
use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * CLI Application with helpers for the Contentful CMA SDK.
 */
class Application extends ConsoleApplication
{
    public function __construct()
    {
        parent::__construct('contentful-management', Client::getVersion());
    }

    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new GenerateEntryClassesCommand();

        return $defaultCommands;
    }
}
