<?php
declare(strict_types=1);

namespace NiceYu\HttpInOut\Dto;

use NiceYu\HttpInOut\AbstractDtoTransformer;

/**
 * item dto object
 * @see ItemDto
 */
class ItemDto extends AbstractDtoTransformer
{
    public string $name;

    public string $value;
}
