<?php declare(strict_types = 1);

namespace Oqq\ZendExpressiveExtension\ServiceContainer\Stub;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\EmitterInterface;

final class Emitter implements EmitterInterface
{
    /** @var null|ResponseInterface */
    private $response;

    public function emit(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
