<?php

namespace Sanchescom\WiFi\Test\Windows\Mocks;

use Sanchescom\WiFi\Test\NetworksCommandAbstract;

/**
 * Class NetworksCommand.
 */
class NetworksCommand extends NetworksCommandAbstract
{
    /** @var string */
    protected static $mock = __DIR__.'/Networks.txt';
}
