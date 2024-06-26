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
    )
    {
    }

    public function toHtml(): string
    {
        $alt = $this->alt ?? '';

        return <<<HTML
            <img src="{$this->src}" alt="{$alt}" width="{$this->width}" height="{$this->height}" />
            HTML;
    }

    public function toText(): ?string
    {
        return $this->alt;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['attrs']['src'],
            $array['attrs']['alt'],
            $array['attrs']['width'],
            $array['attrs']['height'],
        );
    }
}