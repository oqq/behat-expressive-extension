<?php declare(strict_types = 1);

namespace Oqq\ZendExpressiveExtension\Context;

use Interop\Container\ContainerInterface;
use Oqq\ZendExpressiveExtension\ServiceContainer\Stub\Emitter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Application;

class ApplicationContext implements ApplicationAwareContext
{
    /** @var Application */
    private $application;

    /** @var null|ServerRequestInterface */
    private $serverRequest;

    /** @var null|ServerRequestInterface */
    private $response;

    private $applicationWasExecuted = false;

    public function setApplication(Application $application): void
    {
        $this->application = $application;
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->application->getContainer();
    }

    protected function getServerRequest(): ServerRequestInterface
    {
        if (null === $this->serverRequest) {
            $this->serverRequest = new ServerRequest($_SERVER);
        }

        return $this->serverRequest;
    }

    protected function setServerRequest(ServerRequestInterface $request): void
    {
        $this->serverRequest = $request;
    }

    protected function getResponse(): ResponseInterface
    {
        if (null === $this->response) {
            $this->response = new Response();
        }

        return $this->response;
    }

    protected function getFinalResponse(): ResponseInterface
    {
        $this->ensureApplicationWasExecuted();

        $emitter = $this->application->getEmitter();

        if (!$emitter instanceof Emitter) {
            throw new \RuntimeException(sprintf('Could not get response from emitter class "%s"', get_class($emitter)));
        }

        return $emitter->getResponse();
    }

    protected function ensureApplicationWasExecuted(): void
    {
        if (true === $this->applicationWasExecuted) {
            return;
        }

        $this->run();
        $this->applicationWasExecuted = true;
    }

    private function run(): void
    {
        $this->application->run($this->getServerRequest(), $this->response);
    }
}
