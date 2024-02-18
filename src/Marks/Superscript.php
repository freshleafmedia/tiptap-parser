<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Marks;

readonly class Superscript implements Mark
{
    use MarkHasher;

    public function renderOpen(): string
    {
        return '<sup>';
    }

    public function renderClose(): string
    {
        return '</sup>';
    }

    public static function fromArray(array $array): static
    {
        return new static();
    }
}