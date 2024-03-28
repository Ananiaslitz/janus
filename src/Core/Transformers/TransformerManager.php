<?php

namespace Gateway\Core\Transformers;

use Gateway\Core\Contracts\Http\TransformerInterface;

class TransformerManager
{
    private array $transformerInstances = [];

    public function transform($response, array $transformers)
    {
        foreach ($transformers as $transformerClass) {
            $transformer = $this->getTransformerInstance($transformerClass);
            if ($transformer) {
                $response = $transformer->transform($response);
            }
        }
        return $response;
    }

    private function getTransformerInstance($transformerClass)
    {
        if (!isset($this->transformerInstances[$transformerClass]) && class_exists($transformerClass)) {
            $transformer = new $transformerClass();
            if ($transformer instanceof TransformerInterface) {
                $this->transformerInstances[$transformerClass] = $transformer;
            }
        }
        return $this->transformerInstances[$transformerClass] ?? null;
    }
}
