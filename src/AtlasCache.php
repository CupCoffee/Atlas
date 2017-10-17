<?php

namespace Lorey\Atlas;


use Lorey\Atlas\Property\Path;

class AtlasCache
{
    private $resolved = [];

    public function has(Path $path)
    {
        return isset($this->resolved[(string) $path]);
    }
}