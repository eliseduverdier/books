<?php

namespace App\Tests\Dom;
use PHPHtmlParser\Dom as OriginalDom;

class Dom extends OriginalDom
{
    public function findOne(string $selector): ?OriginalDom\Node\AbstractNode
    {
        $nodes = $this->find($selector);
        return $nodes[0] ?? null;
    }

}
