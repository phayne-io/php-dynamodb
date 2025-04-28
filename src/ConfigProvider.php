<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Phayne\DynamoDB\Operation\OperationFactory;
use Phayne\DynamoDB\Service\DynamoDbAdapter;
use Phayne\DynamoDB\Service\DynamoDbAdapterInterface;

/**
 * Class ConfigProvider
 *
 * @package Phayne\DynamoDB
 */
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies()
        ];
    }

    private function getDependencies(): array
    {
        return [
            'factories' => [
                DynamoDbClient::class => Container\DynamoDbClientFactory::class,
                DynamoDbAdapter::class => Container\Service\DynamoDbAdapterFactory::class,
                Marshaler::class => InvokableFactory::class,
                OperationFactory::class => Container\Operation\OperationFactoryFactory::class,
            ],
            'abstract_factories' => [
                Container\DynamoDbClientAbstractFactory::class,
            ],
            'aliases' => [
                DynamoDbAdapterInterface::class => DynamoDbAdapter::class,
            ],
        ];
    }
}
