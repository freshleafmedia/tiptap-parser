<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser\Marks;

readonly class Underline implements Mark
{
    use MarkHasher;

    public function renderOpen(): string
    {
        return '<span style="text-decoration: underline">';
    }

    public function renderClose(): string
    {
        return '</span>';
    }

    public static function fromArray(array $array): static
    {
        return new static();
    }
}