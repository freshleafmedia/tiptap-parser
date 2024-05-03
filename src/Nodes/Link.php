<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use FreshleafMedia\TiptapParser\Sanitiser;

readonly class Link implements Node
{
    use InnerHtmlRendering;

    public function __construct(
        public string $href,
        public ?string $target,
        /** @var array<Node> */
        public array $children = [],
    )
    {
    }

    public function render(): string
    {
        $href = Sanitiser::escape($this->href);
        $target = Sanitiser::escape($this->target);

        return <<<HTML
            <a
                href="{$href}"
                target="{$target}"
            >
                {$this->renderInnerHtml()}
            </a>
            HTML;
    }

    public static function fromArray(array $array): static
    {
        return new static(
            href: $array['attrs']['href'],
            target: $array['attrs']['target'],
            children: $array['children'],
        );
    }
}