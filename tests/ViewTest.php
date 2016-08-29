<?php
namespace pfcode\MeguminFramework;

use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: pfcode
 * Date: 29.08.16
 * Time: 09:59
 */
class ViewTest extends TestCase
{
    public function testPageTitle(){
        $stub = $this->getMockForAbstractClass(View::class);
        $reflectedView = new \ReflectionClass($stub);
        $getPageTitleMethod = $reflectedView->getMethod("getPageTitle");
        $getPageTitleMethod->setAccessible(true);

        View::setPageTitleSeparator("|");

        /**
         * @var View $stub
         */

        // Empty title
        View::setPageTitlePrefix("");
        View::setPageTitleSuffix("");
        $stub->setPageTitle("");
        $this->assertEquals("", $getPageTitleMethod->invoke($stub));

        // Only prefix
        View::setPageTitlePrefix("1");
        View::setPageTitleSuffix("");
        $stub->setPageTitle("");
        $this->assertEquals("1", $getPageTitleMethod->invoke($stub));

        // Only suffix
        View::setPageTitlePrefix("");
        View::setPageTitleSuffix("2");
        $stub->setPageTitle("");
        $this->assertEquals("2", $getPageTitleMethod->invoke($stub));

        // Title with suffix
        View::setPageTitlePrefix("");
        View::setPageTitleSuffix("2");
        $stub->setPageTitle("some title");
        $this->assertEquals("some title|2", $getPageTitleMethod->invoke($stub));

        // Title with prefix
        View::setPageTitlePrefix("1");
        View::setPageTitleSuffix("");
        $stub->setPageTitle("some title");
        $this->assertEquals("1|some title", $getPageTitleMethod->invoke($stub));

        // Prefix & suffix
        View::setPageTitlePrefix("1");
        View::setPageTitleSuffix("2");
        $stub->setPageTitle("");
        $this->assertEquals("1|2", $getPageTitleMethod->invoke($stub));

        // Title with prefix & suffix
        View::setPageTitlePrefix("1");
        View::setPageTitleSuffix("2");
        $stub->setPageTitle("some title");
        $this->assertEquals("1|some title|2", $getPageTitleMethod->invoke($stub));
    }
}