<?php
declare(strict_types=1);

namespace NiceYu\HttpInOut\Dto;

use NiceYu\HttpInOut\AbstractDtoTransformer;

/**
 * array dto object
 * @see ArrayDto
 */
class ArrayDto extends AbstractDtoTransformer
{
    public array $items = [];
}
