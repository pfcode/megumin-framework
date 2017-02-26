<?php

namespace pfcode\MeguminFramework\Architecture\Containers;

abstract class StorageContainer extends ArrayContainer
{
    /**
     * @throws ContainerException
     * @return void
     */
    abstract public function save();

    /**
     * @throws ContainerException
     * @return void
     */
    abstract public function load();
}