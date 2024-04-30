<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser\Nodes;

use FreshleafMedia\TiptapParser\Marks\Mark;

trait RecursiveInnerHtml
{
    protected function getInnerHtml(): string
    {
        /** @var Node|null $previousChildNode */
        $previousChildNode = null;

        $lastKey = $this->content->keys()->last();

        return $this
            ->content
            ->map(function (Node $childNode, $key) use ($lastKey, &$previousChildNode): string {
                $removedKeys = $previousChildNode?->marks->diffKeys($childNode->marks);
                $addedKeys = $childNode->marks->diffKeys($previousChildNode?->marks);

                $isFirstNode = $key === 0;
                $isLastNode = $key === $lastKey;

                if ($isFirstNode) {
                    $addedKeys = $addedKeys->merge($childNode->marks);
                }

                $html = '';
                $html .= $removedKeys?->map(fn (Mark $mark) => $mark->renderClose())->implode('');
                $html .= $addedKeys->map(fn (Mark $mark) => $mark->renderOpen())->implode('');
                $html .= $childNode->render();

                if ($isLastNode) {
                    $html .= $childNode->marks->map(fn (Mark $mark) => $mark->renderClose())->implode('');
                }

                $previousChildNode = $childNode;

                return $html;
            })
            ->implode('');
    }
}
