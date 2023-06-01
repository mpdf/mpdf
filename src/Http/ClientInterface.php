<?php

namespace MpdfAnalize\Http;

use Psr\Http\Message\RequestInterface;

interface ClientInterface
{

	public function sendRequest(RequestInterface $request);

}
