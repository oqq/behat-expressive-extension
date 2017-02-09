<?php declare(strict_types = 1);

namespace Oqq\ZendExpressiveExtension\Context;

use Behat\Behat\Context\Context;
use Zend\Expressive\Application;

interface ApplicationAwareContext extends Context
{
    public function setApplication(Application $application): void;
}
