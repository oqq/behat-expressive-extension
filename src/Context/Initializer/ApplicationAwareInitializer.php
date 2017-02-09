<?php declare(strict_types = 1);

namespace Oqq\ZendExpressiveExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Oqq\ZendExpressiveExtension\Context\ApplicationAwareContext;
use Zend\Expressive\Application;

final class ApplicationAwareInitializer implements ContextInitializer
{
    private $application;

    function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function initializeContext(Context $context): void
    {
        if ($context instanceof ApplicationAwareContext) {
            $context->setApplication($this->application);
        }
    }
}
