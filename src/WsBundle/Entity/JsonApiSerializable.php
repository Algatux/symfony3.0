<?php
declare(strict_types=1);
namespace WsBundle\Entity;

/**
 * Interface JsonApiSerializable
 * @package WsBundle\Entity
 */
interface JsonApiSerializable
{

    public function toJsonApi() : array;
    
}
