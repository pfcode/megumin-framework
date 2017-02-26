<?php
/**
 * Created by PhpStorm.
 * User: pfcode
 * Date: 26.02.17
 * Time: 14:35
 */

namespace pfcode\MeguminFramework;


use pfcode\MeguminFramework\Architecture\Containers\JsonStorageContainer;


class JsonStorageContainerTest extends \PHPUnit_Framework_TestCase
{
    private $temporaryFiles = [];

    public function tearDown()
    {
        foreach($this->temporaryFiles as $temporaryFile){
            if(file_exists($temporaryFile)){
                @unlink($temporaryFile);
            }
        }
    }

    /**
     * @return string
     */
    public function createTemporaryFilename(){
        do{
            $filename = tempnam(sys_get_temp_dir(), md5(time()));
        } while(!file_exists($filename));

        $this->temporaryFiles[] = $filename;

        return $filename;
    }

    public function testSave(){
        $container = new JsonStorageContainer($this->createTemporaryFilename());
        $container->set("testKey", "testValue");
        $container->save();

        $this->assertTrue($container->get("testKey") === "testValue");
    }

    public function testLoad(){
        $filename = $this->createTemporaryFilename();

        $primaryContainer = new JsonStorageContainer($filename);
        $primaryContainer->set("testKey", "testValue");
        $primaryContainer->save();

        // Manual load
        $secondContainer = new JsonStorageContainer($filename);
        $this->assertFalse($secondContainer->has("testKey"));

        $secondContainer->load();
        $this->assertTrue($secondContainer->get("testKey") === "testValue");

        // Load on construct
        $secondContainer = new JsonStorageContainer($filename, true);
        $this->assertTrue($secondContainer->get("testKey") === "testValue");
    }
}
