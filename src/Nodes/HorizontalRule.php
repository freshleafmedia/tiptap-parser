<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Nodes;

use Illuminate\Support\Collection;

readonly class HorizontalRule implements Node
{
    public function __construct(
        public Collection $marks,
    )
    {
    }

    public function render(): string
    {
        return '<hr>';
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['marks'],
        );
    }
}