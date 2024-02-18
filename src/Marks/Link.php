<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Marks;

use FreshleafMedia\TipTapParser\Sanitiser;

readonly class Link implements Mark
{
    use MarkHasher;

    public function __construct(
        public string $href,
        public ?string $target,
    )
    {
    }

    public function renderOpen(): string
    {
        return '<a
            href="' . Sanitiser::escape($this->href) . '"
            target="' . Sanitiser::escape($this->target ?? '') . '"
        >';
    }

    public function renderClose(): string
    {
        return '</a>';
    }

    public static function fromArray(array $array): static
    {
        return new static(
            href: $array['attrs']['href'],
            target: $array['attrs']['target'],
        );
    }
}