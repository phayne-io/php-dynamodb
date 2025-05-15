<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Operation\Query;

use Phayne\DynamoDB\Enum\Operator;

/**
 * Class Criterion
 *
 * @package Phayne\DynamoDB\Operation\Query
 */
final readonly class Criterion
{
    private function __construct(
        public Operator $operator,
        public string $field,
        public mixed $value,
    ) {
    }

    public static function from(Operator $operator, string $field, mixed $value): Criterion
    {
        return new self($operator, $field, $value);
    }

    public function toArray(bool $fieldAsKey = false): array
    {
        return $fieldAsKey
            ? [$this->field => ['operator' => $this->operator->value, 'value' => $this->value]]
            : ['operator' => $this->operator->value, 'name' => $this->field, 'value' => $this->value];
    }
}
