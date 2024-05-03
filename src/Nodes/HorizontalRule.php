<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class HorizontalRule implements Node
{
    public function toHtml(): string
    {
        return '<hr>';
    }

    public function toText(): null
    {
        return null;
    }

    public static function fromArray(array $array): static
    {
        return new static();
    }
}