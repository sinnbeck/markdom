<?php

use function PHPUnit\Framework\assertEquals;

uses(Tests\TestCase::class);

beforeEach(function() {
    $this->blade = app('blade.compiler');
});

test('it can render markdom directive', function() {
    $blade = "@markdom('# subtitle')";
    assertEquals("<?php echo app('markdom')->toHtml('# subtitle'); ?>", $this->blade->compileString($blade));
});

test('it can render style directive', function() {
    $blade = "@markdomStyles()";
    assertEquals("<style>\n<?php echo app('markdom')->getStyles(); ?>\n</style>", $this->blade->compileString($blade));
});

test('it can render style directive with theme', function() {
    $blade = "@markdomStyles('agate')";
    assertEquals("<style>\n<?php echo app('markdom')->getStyles('agate'); ?>\n</style>", $this->blade->compileString($blade));
});