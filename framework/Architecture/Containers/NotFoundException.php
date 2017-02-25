<?php
/**
 * Created by PhpStorm.
 * User: pfcode
 * Date: 25.02.17
 * Time: 14:45
 */

namespace pfcode\MeguminFramework\Architecture\Containers;


use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends \Exception implements NotFoundExceptionInterface
{

}