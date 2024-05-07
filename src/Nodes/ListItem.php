<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class ListItem implements Node
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
            <li>
                {$this->renderInnerHtml()}
            </li>
            HTML;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['children'] ?? [],
        );
    }
}