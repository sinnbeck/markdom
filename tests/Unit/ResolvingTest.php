<?php

use Highlight\Highlighter;
use Sinnbeck\Markdom\Facades\Markdom;
use function PHPUnit\Framework\assertEquals;

uses(Tests\TestCase::class);


test('can resolve by name', function () {
    $markdom = app('markdom');
    assertEquals(\Sinnbeck\Markdom\Markdom::class, get_class($markdom));
    assertEquals(Highlighter::class, get_class($markdom->getHighlighter()));
});

test('can resolve by facade', function () {
    $markdom = Markdom::setClasses([]);
    assertEquals(\Sinnbeck\Markdom\Markdom::class, get_class($markdom));
    assertEquals(Highlighter::class, get_class($markdom->getHighlighter()));
});

test('can resolve by injection', function () {
    $markdom = app()->make(\Sinnbeck\Markdom\Markdom::class);
    assertEquals(\Sinnbeck\Markdom\Markdom::class, get_class($markdom));
    assertEquals(Highlighter::class, get_class($markdom->getHighlighter()));
});