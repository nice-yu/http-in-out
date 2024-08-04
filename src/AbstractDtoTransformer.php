<?php
declare(strict_types=1);

namespace NiceYu\HttpInOut;

use Closure;
use JMS\Serializer\SerializerBuilder;
use stdClass;

abstract class AbstractDtoTransformer implements DtoInterface
{
    /**
     * serialize class object
     * @return string
     */
    public function serialize(): string
    {
        $serialize = SerializerBuilder::create()->build();
        return $serialize->serialize($this, 'json');
    }

    /**
     * class to array
     * @return array
     */
    public function toArray(): array
    {
        $serialize = SerializerBuilder::create()->build();
        return $serialize->toArray($this);
    }

    /**
     * class to response object
     * @param int $code
     * @param string $message
     * @return string
     */
    public function toResponse(int $code = 0, string $message = 'ok'): string
    {
        $o = new stdClass();
        $o->code    = $code;
        $o->message = $message;
        $o->result  = $this;

        $serialize = SerializerBuilder::create()->build();
        return $serialize->serialize($o, 'json');
    }

    /**
     * for class
     * @param iterable $objects
     * @param Closure $closure
     * @return array
     */
    public function forObjects(iterable $objects, Closure $closure):array
    {
        $dto = [];

        foreach ($objects as $object) {
            $dto[] = $closure($object);
        }

        return $dto;
    }
}
