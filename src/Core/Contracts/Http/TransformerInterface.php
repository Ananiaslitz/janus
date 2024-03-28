<?php

namespace Gateway\Core\Contracts\Http;

use Psr\Http\Message\ResponseInterface;

interface TransformerInterface
{
    /**
     * Transforma a resposta recebida em outro formato.
     *
     * @param ResponseInterface $response A resposta original.
     * @return ResponseInterface A resposta transformada.
     */
    public function transform(ResponseInterface $response): ResponseInterface;
}