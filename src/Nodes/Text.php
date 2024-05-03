<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class Text implements Node
{
    public function __construct(
        public string $text,
        public array $marks = [],
    )
    {
    }

    public function render(): string
    {
        return $this->text;
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['text'],
            $array['marks'],
        );
    }
}