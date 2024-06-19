<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

readonly class Superscript implements Node
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
            <sup>
                {$this->renderInnerHtml()}
            </sup>
            HTML;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['children'] ?? [],
        );
    }
}