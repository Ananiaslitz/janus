<?php

namespace Gateway\Core\Services\Http\Request;

interface RequestAdapterInterface
{
    public function getPath();
    public function getMethod();
    public function getHeaders();
    public function setHeaders(array $headers): self;
    public function getBody();
}