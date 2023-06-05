<?php

namespace Mpdf\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{

	public function sendRequest(RequestInterface $request): ResponseInterface;
}
