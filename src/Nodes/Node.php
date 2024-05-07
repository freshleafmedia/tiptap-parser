<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

interface Node
{
    public function toHtml(): string;

    public function toText(): ?string;

    public static function fromArray(array $array): self;
}
