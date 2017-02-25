<?php

namespace pfcode\MeguminFramework\Architecture\Containers;

abstract class StorageContainer extends ArrayContainer {
    /**
     * @throws ContainerException
     * @return bool
     */
    abstract public function save() : bool;

    /**
     * @throws ContainerException
     * @return bool
     */
    abstract public function load() : bool;
}