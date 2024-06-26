<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class Text implements Node
{
    public function __construct(
        public string $text,
    )
    {
    }

    public function toHtml(): string
    {
        return $this->text;
    }

    public function toText(): string
    {
        return $this->text;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['text'],
        );
    }
}