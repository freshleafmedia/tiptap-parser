<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Nodes;

use Illuminate\Support\Collection;

readonly class TaskList implements Node
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
            <ul>
                {$this->getInnerHtml()}
            </ul>
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