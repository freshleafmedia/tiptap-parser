<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

trait InnerTextRendering
{
    public function toText(): string
    {
        return Collection::make($this->children)
            ->map(static fn (Node $node): ?string => $node->toText())
            ->filter()
            ->implode(' ');
    }
}
