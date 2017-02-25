<?php

namespace pfcode\MeguminFramework\Architecture\Containers;

use Psr\Container\ContainerInterface;

class ArrayContainer implements ContainerInterface
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * @param string $id
     * @return mixed
     * @throws NotFoundException
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException();
        }

        return $this->items[$id];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return isset($this->items[$id]);
    }

    /**
     * @param $id
     * @param $item
     */
    public function set($id, $item): void
    {
        $this->items[$id] = $item;
    }

    /**
     * @param $id
     * @throws NotFoundException
     */
    public function remove($id): void
    {
        if (!$this->has($id)) {
            throw new NotFoundException();
        }

        unset($this->items[$id]);
    }

    /**
     * @param $item
     * @return bool
     */
    public function hasObject($item): bool
    {
        foreach ($this->items as $existingItem) {
            if ($item === $existingItem) {
                return true;
            }
        }

        return false;
    }
}