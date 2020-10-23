<?php

use function Spatie\Snapshots\assertMatchesTextSnapshot;

uses(Tests\TestCase::class);

test('it works with empty string', function () {
    $parsed = app('markdom')->toHtml('');
    assertMatchesTextSnapshot($parsed);
});

test('it can read markdown string', function () {
    $parsed = app('markdom')->toHtml('# Hello World!');
    assertMatchesTextSnapshot($parsed);
});

test('it can parse multiple times', function () {
    $parsed1 = app('markdom')->toHtml('# Hello!');
    $parsed2 = app('markdom')->toHtml('# Hello again!');


    assertMatchesTextSnapshot($parsed1);
    assertMatchesTextSnapshot($parsed2);
})->group('a');

test('it can add a class', function () {
    $this->app->config->set('markdom.classes', [
        'h1' => 'title',
    ]);
    $converter = app('markdom');

    assertMatchesTextSnapshot($converter->toHtml('# Hello World!'));
});

test('it can add classes to multiple items', function () {
    $this->app->config->set('markdom.classes', [
        'h1' => 'title',
    ]);
    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
# Hello World!
Lorem ipsum
# Foo bar!
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it can override class', function () {
    $this->app->config->set('markdom.classes', [
        'h1' => 'title',
    ]);
    $converter = app('markdom');
    $converter->setClasses([
        'h1' => 'text-xl'
    ]);

    $markdown =
        <<<MARKDOWN
# Hello World!
Lorem ipsum
# Foo bar!
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it can add multiple classes to multiple items', function () {
    $this->app->config->set('markdom.classes', [
        'h1' => 'text-2xl font-bold uppercase',
        'p' => 'text-gray-500',
    ]);
    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
# Hello World!
Lorem ipsum
# Foo bar!
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it can handle inline code', function () {

    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
This is some `display: inline` code
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it can handle code blocks', function () {
    $converter = app('markdom');

    $markdown =
<<<MARKDOWN
This is a code block
```php
\$arr = [1, 2, 3];
```
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it can parse links', function () {
    $converter = app('markdom');

    $markdown =
        <<<MARKDOWN
[I'm an inline-style link](https://www.google.com)
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it can parse links with title', function () {
    $converter = app('markdom');

    $markdown =
        <<<MARKDOWN
[I'm an inline-style link with title](https://www.google.com "Google's Homepage")
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it can parse file links', function () {
    $converter = app('markdom');

    $markdown =
        <<<MARKDOWN
[I'm a relative reference to a repository file](../blob/master/LICENSE)
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});

test('it can parse img', function () {
    $converter = app('markdom');

    $markdown =
        <<<MARKDOWN
![alt text](https://github.com/sinnbeck/markdom/logo.png "Logo Title Text 1")
MARKDOWN;

    assertMatchesTextSnapshot($converter->toHtml($markdown));
});
