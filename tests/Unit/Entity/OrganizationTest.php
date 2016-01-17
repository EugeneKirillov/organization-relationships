<?php
namespace Owr\Test\Unit\Entity;

use Owr\Entity;

class OrganizationTest extends \PHPUnit_Framework_TestCase
{
    public function testOrganization()
    {
        $entity = new Entity\Organization(100, 'test');

        $this->assertEquals(100, $entity->getId());
        $this->assertEquals('test', $entity->getName());
    }
}
