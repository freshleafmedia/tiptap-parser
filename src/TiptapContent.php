<?php

declare(strict_types=1);

namespace FreshleafMedia\TiptapParser;

use FreshleafMedia\TiptapParser\Nodes\BlockQuote;
use FreshleafMedia\TiptapParser\Nodes\Bold;
use FreshleafMedia\TiptapParser\Nodes\BulletList;
use FreshleafMedia\TiptapParser\Nodes\Code;
use FreshleafMedia\TiptapParser\Nodes\CodeBlock;
use FreshleafMedia\TiptapParser\Nodes\Document;
use FreshleafMedia\TiptapParser\Nodes\HardBreak;
use FreshleafMedia\TiptapParser\Nodes\Heading;
use FreshleafMedia\TiptapParser\Nodes\Highlight;
use FreshleafMedia\TiptapParser\Nodes\HorizontalRule;
use FreshleafMedia\TiptapParser\Nodes\Image;
use FreshleafMedia\TiptapParser\Nodes\Italic;
use FreshleafMedia\TiptapParser\Nodes\Link;
use FreshleafMedia\TiptapParser\Nodes\ListItem;
use FreshleafMedia\TiptapParser\Nodes\Node;
use FreshleafMedia\TiptapParser\Nodes\OrderedList;
use FreshleafMedia\TiptapParser\Nodes\Paragraph;
use FreshleafMedia\TiptapParser\Nodes\Small;
use FreshleafMedia\TiptapParser\Nodes\Strike;
use FreshleafMedia\TiptapParser\Nodes\Subscript;
use FreshleafMedia\TiptapParser\Nodes\Superscript;
use FreshleafMedia\TiptapParser\Nodes\Table;
use FreshleafMedia\TiptapParser\Nodes\TableCell;
use FreshleafMedia\TiptapParser\Nodes\TableHeader;
use FreshleafMedia\TiptapParser\Nodes\TableRow;
use FreshleafMedia\TiptapParser\Nodes\TaskItem;
use FreshleafMedia\TiptapParser\Nodes\TaskList;
use FreshleafMedia\TiptapParser\Nodes\Text;
use FreshleafMedia\TiptapParser\Nodes\Underline;
use Illuminate\Support\Collection;

readonly class TiptapContent
{
    public Collection $nodeFqcnIndex;

    public function __construct(
        public array $content,
        ?Collection $nodeFqcnIndex = null,
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

    protected function createTree(array $tipTapArray): Collection
    {
        $populatedTree = new Collection();

        foreach ($tipTapArray as $nodeData) {
            if ($this->nodeFqcnIndex->has($nodeData['type']) === false) {
                throw new \Exception('Unknown node "' . $nodeData['type'] . '". Try calling registerNode("' . $nodeData['type'] . '", MyNode::class)');
            }

            $nodeFqcn = $this->nodeFqcnIndex->get($nodeData['type']);

            if (array_key_exists('content', $nodeData)) {
                $nodeData['children'] = $this->createTree($nodeData['content'] ?? [])->toArray();
            }

            $node = $nodeFqcn::fromArray([
                ...$nodeData,
            ]);

            if (array_key_exists('marks', $nodeData)) {
                $node = Collection::make($nodeData['marks'])
                    ->reduce(
                        function (Node $child, array $markData) {
                            $nodeFqcn = $this->nodeFqcnIndex->get($markData['type']);

                            return $nodeFqcn::fromArray([
                                ...$markData,
                                'children' => [$child],
                            ]);
                        },
                        $node,
                    );
            }

            $populatedTree->push($node);
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
