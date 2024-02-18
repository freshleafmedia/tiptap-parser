<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Nodes;

use Illuminate\Support\Collection;

readonly class HardBreak implements Node
{
    public function __construct(
        public Collection $marks,
    )
    {
    }

    public function render(): string
    {
        return '<br>';
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['marks'],
        );
    }
}