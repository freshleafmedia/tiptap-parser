<?php

declare(strict_types=1);

namespace FreshleafMedia\TipTapParser;

use FreshleafMedia\TipTapParser\Marks\Bold;
use FreshleafMedia\TipTapParser\Marks\Code;
use FreshleafMedia\TipTapParser\Marks\Highlight;
use FreshleafMedia\TipTapParser\Marks\Italic;
use FreshleafMedia\TipTapParser\Marks\Lead;
use FreshleafMedia\TipTapParser\Marks\Link;
use FreshleafMedia\TipTapParser\Marks\Mark;
use FreshleafMedia\TipTapParser\Marks\Small;
use FreshleafMedia\TipTapParser\Marks\Strike;
use FreshleafMedia\TipTapParser\Marks\Subscript;
use FreshleafMedia\TipTapParser\Marks\Superscript;
use FreshleafMedia\TipTapParser\Marks\Underline;
use FreshleafMedia\TipTapParser\Nodes\BlockQuote;
use FreshleafMedia\TipTapParser\Nodes\BulletList;
use FreshleafMedia\TipTapParser\Nodes\CodeBlock;
use FreshleafMedia\TipTapParser\Nodes\Document;
use FreshleafMedia\TipTapParser\Nodes\HardBreak;
use FreshleafMedia\TipTapParser\Nodes\Heading;
use FreshleafMedia\TipTapParser\Nodes\HorizontalRule;
use FreshleafMedia\TipTapParser\Nodes\Image;
use FreshleafMedia\TipTapParser\Nodes\ListItem;
use FreshleafMedia\TipTapParser\Nodes\Node;
use FreshleafMedia\TipTapParser\Nodes\OrderedList;
use FreshleafMedia\TipTapParser\Nodes\Paragraph;
use FreshleafMedia\TipTapParser\Nodes\Table;
use FreshleafMedia\TipTapParser\Nodes\TableCell;
use FreshleafMedia\TipTapParser\Nodes\TableHeader;
use FreshleafMedia\TipTapParser\Nodes\TableRow;
use FreshleafMedia\TipTapParser\Nodes\TaskItem;
use FreshleafMedia\TipTapParser\Nodes\TaskList;
use FreshleafMedia\TipTapParser\Nodes\Text;
use Illuminate\Support\Collection;

readonly class Parser
{
    public Collection $nodeFqcnIndex;
    public Collection $markFqcnIndex;

    public function __construct(
        public array $tipTapArray,
        ?Collection $nodeFqcnIndex = null,
        ?Collection $markFqcnIndex = null,
    )
    {
        $this->nodeFqcnIndex = $nodeFqcnIndex ?? Collection::make([
            'blockquote' => BlockQuote::class,
            'bulletList' => BulletList::class,
            'codeBlock' => CodeBlock::class,
            'doc' => Document::class,
            'hardBreak' => HardBreak::class,
            'heading' => Heading::class,
            'horizontalrule' => HorizontalRule::class,
            'image' => Image::class,
            'listItem' => ListItem::class,
            'orderedList' => OrderedList::class,
            'paragraph' => Paragraph::class,
            'table' => Table::class,
            'tableCell' => TableCell::class,
            'tableRow' => TableRow::class,
            'tableHeader' => TableHeader::class,
            'taskItem' => TaskItem::class,
            'taskList' => TaskList::class,
            'text' => Text::class,
        ]);

        $this->markFqcnIndex = $markFqcnIndex ?? Collection::make([
            'bold' => Bold::class,
            'code' => Code::class,
            'highlight' => Highlight::class,
            'italic' => Italic::class,
            'link' => Link::class,
            'small' => Small::class,
            'strike' => Strike::class,
            'superscript' => Superscript::class,
            'subscript' => Subscript::class,
            'underline' => Underline::class,
        ]);
    }

    public function registerNode(string $type, string $fqcn): self
    {
        return new self($this->tipTapArray, $this->nodeFqcnIndex->put($type, $fqcn), $this->markFqcnIndex);
    }

    public function registerMark(string $type, string $fqcn): self
    {
        return new self($this->tipTapArray, $this->nodeFqcnIndex, $this->markFqcnIndex->put($type, $fqcn));
    }

    protected function createTree(array $tipTapArray): Collection
    {
        $populatedTree = new Collection();

        foreach ($tipTapArray as $node) {
            if ($this->nodeFqcnIndex->has($node['type']) === false) {
                throw new \Exception('Unknown node "' . $node['type'] . '". Try calling registerNode("' . $node['type'] . '", MyNode::class)');
            }

            $nodeFqcn = $this->nodeFqcnIndex->get($node['type']);

            $content = $this->createTree($node['content'] ?? []);

            $marks = Collection::make($node['marks'] ?? [])
                ->map(function (array $markData): Mark {
                    if ($this->markFqcnIndex->has($markData['type']) === false) {
                        throw new \Exception('Unknown mark "' . $markData['type'] . '". Try calling registerMark("' . $markData['type'] . '", MyMark::class)');
                    }

                    $markFqcn = $this->markFqcnIndex->get($markData['type']);

                    return $markFqcn::fromArray($markData);
                })
                ->keyBy(fn (Mark $mark): string => $mark->getHash());

            $nodeInstance = $nodeFqcn::fromArray([
                ...$node,
                'content' => $content,
                'marks' => $marks,
            ]);

            $populatedTree->push($nodeInstance);
        }

        return $populatedTree;
    }

    public function toHtml(): string
    {
        return $this
            ->createTree($this->tipTapArray)
            ->map(fn (Node $node): string => $node->render())
            ->implode('');
    }

    public static function fromArray($tipTapArray): self
    {
        return new self($tipTapArray);
    }

    public function lookupNode(string $nodeName): string
    {
        return $this->nodeFqcnIndex[$nodeName];
    }
}
