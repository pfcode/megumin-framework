<?php

namespace pfcode\MeguminFramework\Architecture\Containers;


class JsonStorageContainer extends StorageContainer
{
    /**
     * @var string
     */
    private $filename;

    /**
     * JsonStorageContainer constructor.
     * @param string $filename
     * @param bool $loadOnConstruct
     */
    public function __construct(string $filename, bool $loadOnConstruct = false)
    {
        $this->filename = $filename;

        if ($loadOnConstruct) {
            $this->load();
        }
    }

    /**
     * @throws ContainerException
     */
    public function save()
    {
        $jsonItems = json_encode($this->items);
        if ($jsonItems === false) {
            throw new ContainerException("Failed to JSON-encode container");
        }

        if (file_put_contents($this->filename, $jsonItems) === false) {
            throw new ContainerException("Failed to save container to file");
        }
    }

    /**
     * @throws ContainerException
     */
    public function load()
    {
        $rawJson = file_get_contents($this->filename);
        if($rawJson === false){
            throw new ContainerException("Failed to read container from file");
        }

        $items = json_decode($rawJson, JSON_OBJECT_AS_ARRAY);
        if($items === false){
            throw new ContainerException("Failed to JSON-decode file");
        }
    }
}