<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

readonly class Code implements Node
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
            <code>
                {$this->renderInnerHtml()}
            </code>
            HTML;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['children'] ?? [],
        );
    }
}