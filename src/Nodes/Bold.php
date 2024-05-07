<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

readonly class Bold implements Node
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
            <strong>
                {$this->renderInnerHtml()}
            </strong>
            HTML;
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['children'] ?? [],
        );
    }
}