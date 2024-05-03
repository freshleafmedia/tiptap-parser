<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class Heading implements Node
{
    use InnerHtmlRendering;
    use InnerTextRendering;

    public function __construct(
        public int $level,
        /** @var array<Node> */
        public array $children = [],
    )
    {
    }

    public function toHtml(): string
    {
        return <<<HTML
            <h{$this->level}>
                {$this->renderInnerHtml()}
            </h{$this->level}>
            HTML;
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['attrs']['level'],
            $array['children'],
        );
    }
}