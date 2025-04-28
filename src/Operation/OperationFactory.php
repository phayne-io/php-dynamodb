<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Phayne\DynamoDB\Util\Inflector;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

/**
 * Class OperationFactory
 *
 * @package Phayne\DynamoDB\Operation
 */
final readonly class OperationFactory
{
    public function __construct(private DynamoDbClient $dynamoDbClient, private readonly Marshaler $marshaler)
    {
    }

    public function factory(string $type, ...$options): OperationInterface
    {
        try {
            $className = sprintf(
                '%s\%sOperation',
                __NAMESPACE__,
                Inflector::camelize($type)
            );
            /** @psalm-suppress ArgumentTypeCoercion */
            $reflectionClass = new ReflectionClass($className);
            $args = [$this->dynamoDbClient, $this->marshaler];
            foreach ($options as $option) {
                $args[] = $option;
            }
            /** @var OperationInterface $instance */
            $instance = $reflectionClass->newInstanceArgs($args);

            if (! $instance instanceof OperationInterface) {
                throw new UnexpectedValueException(
                    sprintf('The %s class is not %s', $type, OperationInterface::class)
                );
            }
            return $instance;
        } catch (ReflectionException) {
            throw new UnexpectedValueException(
                sprintf('The %s operation does not exist', $type)
            );
        }
    }
}
