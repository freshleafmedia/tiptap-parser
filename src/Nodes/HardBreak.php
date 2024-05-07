<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class HardBreak implements Node
{

    public function toHtml(): string
    {
        return '<br>';
    }

    public function toText(): null
    {
        return null;
    }

    public static function fromArray(array $array): self
    {
        return new self();
    }
}