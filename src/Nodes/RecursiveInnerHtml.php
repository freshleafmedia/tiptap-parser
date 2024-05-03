<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use FreshleafMedia\TiptapParser\Marks\Mark;
use Illuminate\Support\Collection;

trait RecursiveInnerHtml
{
    protected function getInnerHtml(): string
    {
        /** @var Node|null $previousChildNode */
        $previousChildNode = null;

        $content = Collection::make($this->content);

        $lastKey = $content->keys()->last();

        return $content
            ->map(function (Node $childNode, $key) use ($lastKey, &$previousChildNode): string {
                $childMarks = Collection::make($childNode->marks);
                $previousChildMarks = Collection::make($previousChildNode?->marks);

                $removedKeys = $previousChildMarks->diffKeys($childMarks);
                $addedKeys = $childMarks->diffKeys($previousChildMarks);

                $isFirstNode = $key === 0;
                $isLastNode = $key === $lastKey;

                if ($isFirstNode) {
                    $addedKeys = $addedKeys->merge($childMarks);
                }

                $html = '';
                $html .= $removedKeys?->map(fn (Mark $mark) => $mark->renderClose())->implode('');
                $html .= $addedKeys->map(fn (Mark $mark) => $mark->renderOpen())->implode('');
                $html .= $childNode->render();

                if ($isLastNode) {
                    $html .= $childMarks->map(fn (Mark $mark) => $mark->renderClose())->implode('');
                }

                $previousChildNode = $childNode;

                return $html;
            })
            ->implode('');
    }
}
