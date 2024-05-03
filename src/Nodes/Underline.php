<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

readonly class Underline implements Node
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
            <span style="text-decoration: underline">
                {$this->renderInnerHtml()}
            </span>
        HTML;
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['children'] ?? [],
        );
    }
}