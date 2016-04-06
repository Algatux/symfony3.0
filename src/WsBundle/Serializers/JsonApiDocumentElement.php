<?php
declare(strict_types=1);
namespace WsBundle\Serializers;

use JsonSerializable;

/**
 * Class JsonApiDocumentElement
 * @package WsBundle\Serializers
 */
class JsonApiDocumentElement implements JsonSerializable
{

    /** @var int  */
    private $id;

    /** @var string  */
    private $type;

    /** @var array  */
    private $attributes;

    /**
     * JsonApiDocumentElement constructor.
     * @param int $id
     * @param string $type
     * @param array $attributes
     */
    public function __construct(int $id, string $type, array $attributes = [])
    {
        $this->id = $id;
        $this->attributes = $attributes;

        $class = new \ReflectionClass($type);
        $this->type = strtolower($class->getShortName());
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {

        return [
            "id" => $this->id,
            "type" => $this->type,
            "attributes" => $this->attributes
        ];
    }
}