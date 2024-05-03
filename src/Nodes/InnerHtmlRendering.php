<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use Illuminate\Support\Collection;

trait InnerHtmlRendering
{
    protected function renderInnerHtml(): string
    {
        return Collection::make($this->children)
            ->map(static fn (Node $node): string => $node->render())
            ->implode('');
    }
}
