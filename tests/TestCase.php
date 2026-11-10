<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        if (!SQLiteGrammar::hasMacro('typeSet')) {
            SQLiteGrammar::macro('typeSet', function () {
                return 'text';
            });
        }
    }
}
