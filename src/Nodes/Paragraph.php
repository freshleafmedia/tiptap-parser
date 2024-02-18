<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Nodes;

use Illuminate\Support\Collection;

readonly class Paragraph implements Node
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
            <p>
                {$this->getInnerHtml()}
            </p>
        HTML;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['content'],
            $array['marks'],
        );
    }
}