<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Container;

use Aws\DynamoDb\DynamoDbClient;
use Psr\Container\ContainerInterface;

/**
 * Class DynamoDbClientFactory
 *
 * @package Phayne\DynamoDB\Container
 */
final class DynamoDbClientFactory
{
    public function __invoke(ContainerInterface $container): DynamoDbClient
    {
        $config = $container->get('config');
        $dynamoConfig = $config['dynamo']['client'] ?? [];
        $awsConfig = $config['aws'] ?? [];

        $clientConfig = array_merge($awsConfig, $dynamoConfig);

        return new DynamoDbClient($clientConfig);
    }
}
