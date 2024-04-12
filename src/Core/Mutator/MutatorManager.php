<?php

namespace Gateway\Core\Mutator;

class MutatorManager
{
    private array $mutatorInstance = [];

    public function mutate($request, array $mutators)
    {
        foreach ($mutators as $mutatorClass) {
            $mutator = $this->getMutatorInstance($mutatorClass);
            if ($mutator) {
                $request = $mutator->mutate($request);
            }
        }
        return $request;
    }

    private function getMutatorInstance($mutatorClass)
    {
        if (!isset($this->mutatorInstance[$mutatorClass]) && class_exists($mutatorClass)) {
            $mutator = new $mutatorClass();
            if ($mutator instanceof MutatorInterface) {
                $this->mutatorInstance[$mutatorClass] = $mutatorClass;
            }
        }
        return $mutator ?? null;
    }
}