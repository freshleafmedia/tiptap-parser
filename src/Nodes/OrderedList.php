<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class OrderedList implements Node
{
    use InnerHtmlRendering;
    use InnerTextRendering;

    public function __construct(
        /** @var array<Node> */
        public array $children = [],
    )
    {
    }

    public function toHtml(): string
    {
        return <<<HTML
            <ol>
                {$this->renderInnerHtml()}
            </ol>
            HTML;
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['children'] ?? [],
        );
    }
}