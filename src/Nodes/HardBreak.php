<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class HardBreak implements Node
{

    public function render(): string
    {
        return '<br>';
    }

    public static function fromArray(array $array): static
    {
        return new static();
    }
}