<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Exception;

use Aws\DynamoDb\Exception\DynamoDbException;
use Exception;
use ReflectionClass;
use ReflectionException;

/**
 * Class ExceptionFactory
 *
 * @package Phayne\DynamoDB\Exception
 */
class ExceptionFactory
{
    public static function factory(DynamoDbException $ex): Exception
    {
        try {
            $className = sprintf(
                '%s\%s',
                'Phayne\DynamoDB\Exception',
                $ex->getAwsErrorCode()
            );
            $reflectionClass = new ReflectionClass($className);
            $args = [sprintf('An error has occurred => %s', $ex->getAwsErrorMessage()), $ex->getCode()];
            return $reflectionClass->newInstanceArgs($args);
        } catch (ReflectionException $ex) {
            return new Exception($ex->getMessage(), $ex->getCode());
        }
    }
}
