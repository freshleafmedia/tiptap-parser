<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

interface Node
{
    public function render(): string;

    public static function fromArray(array $array): static;
}
