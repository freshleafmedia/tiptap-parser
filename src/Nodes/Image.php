<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

readonly class Image implements Node
{
    public function __construct(
        public string $src,
        public ?string $alt,
        public int $width,
        public int $height,
        public array $marks = [],
    )
    {
    }

    public function render(): string
    {
        $alt = $this->alt ?? '';

        return <<<HTML
            <img src="{$this->src}" alt="{$alt}" width="{$this->width}" height="{$this->height}" />
        HTML;
    }

    public static function fromArray(array $array): static
    {
        return new static(
            $array['attrs']['src'],
            $array['attrs']['alt'],
            $array['attrs']['width'],
            $array['attrs']['height'],
            $array['marks'],
        );
    }
}