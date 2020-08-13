<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

use Arvato\AfterpayModule\Core\Events;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class EventsTest: Tests for arvatoAfterpayEvents.
 */
class EventsTest extends UnitTestCase
{

    /**
     * setUp helper
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Testing method onActivate
     * The actual test is that there is no oxAdoDbException thrown,
     * i.e. the query works just fine.
     */
    public function testOnActivate()
    {
        $sutReturn = Events::onActivate();
        $this->assertTrue($sutReturn);
        // Must be idempotent - let's repeat
        $sutReturn = Events::onActivate();
        $this->assertTrue($sutReturn);
    }
}
