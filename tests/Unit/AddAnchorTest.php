<?php

use Sinnbeck\Markdom\Exceptions\MethodNotAllowedException;
use function Spatie\Snapshots\assertMatchesTextSnapshot;

uses(Tests\TestCase::class);

beforeEach(function () {
    app()->config->set('markdom.anchor_tags.enabled', true);
});

test('it adds an anchor tag before', function () {
    app()->config->set('markdom.anchor_tags.position', 'before');
    app()->config->set('markdom.anchor_tags.elements', [
        'h1',
        'h2',
    ]);

    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
# Hello World!
## Lorem ipsum
Lorem ipsum
### Foo bar!
#### Bar baz
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it adds an anchor tag after', function () {
    app()->config->set('markdom.anchor_tags.position', 'after');
    app()->config->set('markdom.anchor_tags.elements', [
        'h1',
        'h2',
    ]);


    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
# Hello World!
## Lorem ipsum
Lorem ipsum
### Foo bar!
#### Bar baz
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it wraps an anchor tag', function () {
    app()->config->set('markdom.anchor_tags.position', 'wrap');
    app()->config->set('markdom.anchor_tags.elements', [
        'h1',
        'h2',
    ]);


    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
# Hello World!
## Lorem ipsum
Lorem ipsum
### Foo bar!
#### Bar baz
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it add an anchor tag prepend', function () {
    app()->config->set('markdom.anchor_tags.position', 'prepend');
    app()->config->set('markdom.anchor_tags.elements', [
        'h1',
        'h2',
    ]);


    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
# Hello World!
## Lorem ipsum
Lorem ipsum
### Foo bar!
#### Bar baz
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it add an anchor tag wrapping content', function () {
    app()->config->set('markdom.anchor_tags.position', 'wrapInner');
    app()->config->set('markdom.anchor_tags.elements', [
        'h1',
        'h2',
    ]);


    $converter = app('markdom');

    $markdown =
        <<<MARKDOWN
# Hello World!
## Lorem ipsum
Lorem ipsum
### Foo bar!
#### Bar baz
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it add an anchor tag append', function () {
    app()->config->set('markdom.anchor_tags.position', 'append');
    app()->config->set('markdom.anchor_tags.elements', [
        'h1',
        'h2',
    ]);


    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
# Hello World!
## Lorem ipsum
Lorem ipsum
### Foo bar!
#### Bar baz
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it does not add href if disabled', function () {
    app()->config->set('markdom.anchor_tags.disable_href', true);
    app()->config->set('markdom.anchor_tags.add_id_to', 'a');
    app()->config->set('markdom.anchor_tags.elements', [
        'h1',
        'h2',
    ]);

    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
# Hello World!
## Lorem ipsum
Lorem ipsum
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it removes a tag if not needed', function () {
    app()->config->set('markdom.anchor_tags.disable_href', true);
    app()->config->set('markdom.anchor_tags.elements', [
        'h1',
        'h2',
    ]);

    $converter = app('markdom');

    $markdown =
        <<<MARKDOWN
# Hello World!
## Lorem ipsum
Lorem ipsum
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it throws an exception if position is unknown', function () {

    $this->expectException(MethodNotAllowedException::class);

    app()->config->set('markdom.anchor_tags.position', 'foo');
    app()->config->set('markdom.anchor_tags.elements', [
        'h1',
        'h2',
    ]);

    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
# Hello World!
## Lorem ipsum
Lorem ipsum
### Foo bar!
#### Bar baz
MARKDOWN;

    $converter->toHtml($markdown);
});
