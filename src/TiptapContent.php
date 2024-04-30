<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser;

use FreshleafMedia\TiptapParser\Marks\Bold;
use FreshleafMedia\TiptapParser\Marks\Code;
use FreshleafMedia\TiptapParser\Marks\Highlight;
use FreshleafMedia\TiptapParser\Marks\Italic;
use FreshleafMedia\TiptapParser\Marks\Lead;
use FreshleafMedia\TiptapParser\Marks\Link;
use FreshleafMedia\TiptapParser\Marks\Mark;
use FreshleafMedia\TiptapParser\Marks\Small;
use FreshleafMedia\TiptapParser\Marks\Strike;
use FreshleafMedia\TiptapParser\Marks\Subscript;
use FreshleafMedia\TiptapParser\Marks\Superscript;
use FreshleafMedia\TiptapParser\Marks\Underline;
use FreshleafMedia\TiptapParser\Nodes\BlockQuote;
use FreshleafMedia\TiptapParser\Nodes\BulletList;
use FreshleafMedia\TiptapParser\Nodes\CodeBlock;
use FreshleafMedia\TiptapParser\Nodes\Document;
use FreshleafMedia\TiptapParser\Nodes\HardBreak;
use FreshleafMedia\TiptapParser\Nodes\Heading;
use FreshleafMedia\TiptapParser\Nodes\HorizontalRule;
use FreshleafMedia\TiptapParser\Nodes\Image;
use FreshleafMedia\TiptapParser\Nodes\ListItem;
use FreshleafMedia\TiptapParser\Nodes\Node;
use FreshleafMedia\TiptapParser\Nodes\OrderedList;
use FreshleafMedia\TiptapParser\Nodes\Paragraph;
use FreshleafMedia\TiptapParser\Nodes\Table;
use FreshleafMedia\TiptapParser\Nodes\TableCell;
use FreshleafMedia\TiptapParser\Nodes\TableHeader;
use FreshleafMedia\TiptapParser\Nodes\TableRow;
use FreshleafMedia\TiptapParser\Nodes\TaskItem;
use FreshleafMedia\TiptapParser\Nodes\TaskList;
use FreshleafMedia\TiptapParser\Nodes\Text;
use Illuminate\Support\Collection;

readonly class TiptapContent
{
    public Collection $nodeFqcnIndex;
    public Collection $markFqcnIndex;

    public function __construct(
        public array $content,
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
        $this->nodeFqcnIndex->put($type, $fqcn);

        return $this;
    }

    public function registerMark(string $type, string $fqcn): static
    {
        $this->markFqcnIndex->put($type, $fqcn);

        return $this;
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
            ->createTree($this->content)
            ->map(fn (Node $node): string => $node->render())
            ->implode('');
    }

    public static function fromArray($tipTapArray): self
    {
        if (array_is_list($tipTapArray) === false) {
            $tipTapArray = [$tipTapArray];
        }

        return new static($tipTapArray);
    }

    public function lookupNode(string $nodeName): string
    {
        return $this->nodeFqcnIndex[$nodeName];
    }
}
