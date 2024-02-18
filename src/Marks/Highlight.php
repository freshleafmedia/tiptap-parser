<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Marks;

readonly class Highlight implements Mark
{
    use MarkHasher;

    public function renderOpen(): string
    {
        return '<mark>';
    }

    public function renderClose(): string
    {
        return '</mark>';
    }

    public static function fromArray(array $array): static
    {
        return new static();
    }
}