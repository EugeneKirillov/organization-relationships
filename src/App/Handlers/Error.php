<?php
namespace Owr\App\Handlers;

use Owr\Exception\InvalidArgumentException;
use Owr\Exception\NotFoundException;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Slim\Handlers\Error as SlimError;
use Slim\Http\Body;

/**
 * Class Error
 *
 * TODO: refactor error handler flow
 *
 * @package Owr\App\Handlers
 */
class Error extends SlimError
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, \Exception $exception)
    {
        if ($exception instanceof InvalidArgumentException) {
            return $this->getBadRequestResponse($request, $response, $exception);
        } elseif ($exception instanceof NotFoundException) {
            return $this->getNotFoundResponse($request, $response, $exception);
        }

        return parent::__invoke($request, $response, $exception);
    }

    protected function getBadRequestResponse(
        RequestInterface $request,
        ResponseInterface $response,
        \Exception $exception
    ) {
        $output = json_encode([
            'error_message' => $exception->getMessage(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $body = new Body(fopen('php://temp', 'r+'));
        $body->write($output);

        return $response
            ->withStatus(400)
            ->withHeader('Content-type', 'application/json')
            ->withBody($body);
    }

    protected function getNotFoundResponse(
        RequestInterface $request,
        ResponseInterface $response,
        \Exception $exception
    ) {
        $output = json_encode([
            'error_message' => $exception->getMessage(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $body = new Body(fopen('php://temp', 'r+'));
        $body->write($output);

        return $response
            ->withStatus(404)
            ->withHeader('Content-type', 'application/json')
            ->withBody($body);
    }

    protected function renderJsonErrorMessage(\Exception $exception)
    {
        return json_encode([
            'error_code'    => $exception->getCode(),
            'error_message' => $exception->getMessage(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
