<?php

namespace App\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpErrorResponse extends \RuntimeException
{
    /**
     * @var RequestInterface
     */
    public $request;

    /**
     * @var ResponseInterface
     */
    public $response;

    public function __construct(ResponseInterface $response, RequestInterface $request)
    {
        $this->response = $response;
        $this->request = $request;

        parent::__construct($response->getBody());
    }
}
