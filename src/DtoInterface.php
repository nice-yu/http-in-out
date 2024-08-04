<?php
declare(strict_types=1);

namespace NiceYu\HttpInOut;

interface DtoInterface
{
    public function serialize(): string;

    public function toArray(): array;

    public function toResponse(int $code, string $message): string;
}