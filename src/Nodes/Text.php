<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Nodes;

use Illuminate\Support\Collection;

readonly class Text implements Node
{
    public function __construct(
        public string $text,
        public Collection $marks,
    )
    {
    }

    public function render(): string
    {
        return $this->text;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['text'],
            $array['marks'],
        );
    }
}