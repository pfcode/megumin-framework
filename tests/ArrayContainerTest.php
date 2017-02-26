<?php

namespace pfcode\MeguminFramework;


use pfcode\MeguminFramework\Architecture\Containers\ArrayContainer;
use pfcode\MeguminFramework\Architecture\Containers\NotFoundException;


class ArrayContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testHas(): void
    {
        $container = $this->createMockContainer();

        $this->assertTrue($container->has("testKey"));
        $this->assertFalse($container->has(0));
    }

    public function testGet(): void
    {
        $container = $this->createMockContainer();

        $this->assertTrue($container->get("testKey") === "Hello, World!");
        $this->assertTrue($container->get("otherTestKey") === "Hello, ArrayContainer!");
    }

    public function testHasObject(): void
    {
        $container = $this->createMockContainer();

        $this->assertTrue($container->hasObject("Hello, World!"));
        $this->assertFalse($container->hasObject("Goodbye, World!"));
    }

    public function testRemove(): void
    {
        $container = $this->createMockContainer();

        $container->remove("testKey");
        $container->remove("otherTestKey");

        $this->expectException(NotFoundException::class);
        $container->get("otherTestKey");
    }

    public function testCount(): void
    {
        $container = $this->createMockContainer();

        $this->assertTrue($container->count() === 2);
    }

    public function testNotExistingKeyExceptionOnGet(): void
    {
        $this->expectException(NotFoundException::class);

        $container = new ArrayContainer();
        $container->get("notExistingKey");
    }

    public function testNotExistingKeyExceptionOnRemove(): void
    {
        $this->expectException(NotFoundException::class);

        $container = new ArrayContainer();
        $container->remove("notExistingKey");
    }

    /**
     * @return ArrayContainer
     */
    private function createMockContainer(): ArrayContainer
    {
        $container = new ArrayContainer();

        $container->set("testKey", "Hello, World!");
        $container->set("otherTestKey", "Hello, ArrayContainer!");

        return $container;
    }
}
