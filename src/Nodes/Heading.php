<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Nodes;

use Illuminate\Support\Collection;

readonly class Heading implements Node
{
    use RecursiveInnerHtml;

    public function __construct(
        public int $level,
        public Collection $content,
        public Collection $marks,
    )
    {
    }

    public function render(): string
    {
        return <<<HTML
            <h{$this->level}>
                {$this->getInnerHtml()}
            </h>
        HTML;
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['attrs']['level'],
            $array['content'],
            $array['marks'],
        );
    }
}