<?php

/**
 *
 */

namespace Arvato\AfterpayModule\Tests\Unit\Core;

/**
 * Class EventsTest: Tests for arvatoAfterpayEvents.
 */
class EventsTest extends \OxidEsales\TestingLibrary\UnitTestCase
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
        $sutReturn = \Arvato\AfterpayModule\Core\Events::onActivate();
        $this->assertTrue($sutReturn);
        // Must be idempotent - let's repeat
        $sutReturn = \Arvato\AfterpayModule\Core\Events::onActivate();
        $this->assertTrue($sutReturn);
    }
}
