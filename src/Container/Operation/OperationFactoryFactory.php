<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Container\Operation;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Phayne\DynamoDB\Operation\OperationFactory;
use Psr\Container\ContainerInterface;

/**
 * Class OperationFactoryFactory
 *
 * @package Phayne\DynamoDB\Container\Operation
 */
final class OperationFactoryFactory
{
    public function __invoke(ContainerInterface $container): OperationFactory
    {
        return new OperationFactory(
            $container->get(DynamoDbClient::class),
            $container->get(Marshaler::class)
        );
    }
}
