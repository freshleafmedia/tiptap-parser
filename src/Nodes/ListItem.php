<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Nodes;

use Illuminate\Support\Collection;

readonly class ListItem implements Node
{
    use RecursiveInnerHtml;

    public function __construct(
        public Collection $content,
        public Collection $marks,
    )
    {
    }

    public function render(): string
    {
        return <<<HTML
            <li>
                {$this->getInnerHtml()}
            </li>
        HTML;
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['content'],
            $array['marks'],
        );
    }
}