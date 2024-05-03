<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class CodeBlock implements Node
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
            <pre>
                <code>{$this->renderInnerHtml()}</code>
            </pre>
            HTML;
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['children'] ?? [],
        );
    }
}