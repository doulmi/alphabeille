<?php

namespace App\Editor\Markdown;

class Markdown {
    protected  $parser;

    public function __construct(Parser $parser)
    {
       $this->parser = $parser;
    }

    public function parse($text) {
        return $this->parser->makeHtml($text);
    }
}