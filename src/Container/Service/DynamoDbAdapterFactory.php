<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Container\Service;

use Phayne\DynamoDB\Operation\OperationFactory;
use Phayne\DynamoDB\Service\DynamoDbAdapter;
use Psr\Container\ContainerInterface;

/**
 * Class DynamoDbAdapterFactory
 *
 * @package Phayne\DynamoDB\Container\Service
 */
final class DynamoDbAdapterFactory
{
    public function __invoke(ContainerInterface $container): DynamoDbAdapter
    {
        return new DynamoDbAdapter(
            $container->get(OperationFactory::class),
        );
    }
}
