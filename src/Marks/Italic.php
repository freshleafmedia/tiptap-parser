<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Marks;

readonly class Italic implements Mark
{
    use MarkHasher;

    public function renderOpen(): string
    {
        return '<em>';
    }

    public function renderClose(): string
    {
        return '</em>';
    }

    public static function fromArray(array $array): static
    {
        return new static();
    }
}
