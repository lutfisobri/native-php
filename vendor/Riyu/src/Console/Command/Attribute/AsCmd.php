<?php

namespace Riyu\Console\Command\Attribute;

class AsCmd
{
    public function __construct(
        public string $name,
        public array $aliases = [],
        public ?string $description = null
    ) {
        $name = explode('|', $name);
        $name = array_merge($name, $aliases);

        $this->name = implode('|', $name);
    }
}