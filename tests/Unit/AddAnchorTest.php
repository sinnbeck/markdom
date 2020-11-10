<?php

use Sinnbeck\Markdom\Exceptions\MethodNotAllowedException;
use function Spatie\Snapshots\assertMatchesTextSnapshot;

uses(Tests\TestCase::class);

test('it adds an anchor tag before if enabled', function () {
    $this->app->config->set('markdom.anchor_tags.enabled', true);
    $this->app->config->set('markdom.anchor_tags.position', 'before');
    $this->app->config->set('markdom.anchor_tags.elements', [
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

test('it adds an anchor tag after if enabled', function () {
    $this->app->config->set('markdom.anchor_tags.enabled', true);
    $this->app->config->set('markdom.anchor_tags.position', 'after');
    $this->app->config->set('markdom.anchor_tags.elements', [
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

test('it wraps an anchor tag if enabled', function () {
    $this->app->config->set('markdom.anchor_tags.enabled', true);
    $this->app->config->set('markdom.anchor_tags.position', 'wrap');
    $this->app->config->set('markdom.anchor_tags.elements', [
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

test('it add an anchor tag prepend if enabled', function () {
    $this->app->config->set('markdom.anchor_tags.enabled', true);
    $this->app->config->set('markdom.anchor_tags.position', 'prepend');
    $this->app->config->set('markdom.anchor_tags.elements', [
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

test('it add an anchor tag append if enabled', function () {
    $this->app->config->set('markdom.anchor_tags.enabled', true);
    $this->app->config->set('markdom.anchor_tags.position', 'append');
    $this->app->config->set('markdom.anchor_tags.elements', [
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

test('it throws an exception if position is unknown', function () {

    $this->expectException(MethodNotAllowedException::class);

    $this->app->config->set('markdom.anchor_tags.enabled', true);
    $this->app->config->set('markdom.anchor_tags.position', 'foo');
    $this->app->config->set('markdom.anchor_tags.elements', [
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
