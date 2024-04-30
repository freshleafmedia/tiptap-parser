<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class Document implements Node
{
    use RecursiveInnerHtml;

    public function __construct(
        public Collection $content,
    )
    {
    }

    public function render(): string
    {
        return $this->getInnerHtml();
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['content'],
        );
    }
}