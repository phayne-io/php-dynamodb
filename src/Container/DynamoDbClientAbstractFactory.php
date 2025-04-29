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

use Aws\Credentials\AssumeRoleCredentialProvider;
use Aws\Credentials\CredentialProvider;
use Aws\DynamoDb\DynamoDbClient;
use Aws\Sts\StsClient;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * Class DynamoDbClientAbstractFactory
 *
 * @package Phayne\DynamoDB\Container
 */
final class DynamoDbClientAbstractFactory implements AbstractFactoryInterface
{
    private ?array $clientConfig = null;

    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        $clientConfig = $this->getConfig($container);
        $awsConfig = $container->get('config')['aws'] ?? [];

        if (empty($clientConfig) || empty($awsConfig)) {
            return false;
        }

        return isset($clientConfig[$requestedName])
            && is_array($clientConfig[$requestedName])
            && ! empty($clientConfig[$requestedName]);
    }

    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): DynamoDbClient
    {
        $config = $this->getConfig($container);
        $clientConfig = $config[$requestedName];
        $awsConfig = $container->get('config')['aws'] ?? [];

        if (isset($clientConfig['assume_role_params']) && ! empty($clientConfig['assume_role_params']['RoleArn'])) {
            $assumeRoleCredentials = new AssumeRoleCredentialProvider([
                'client' => new StsClient([
                    'region' => $clientConfig['region'],
                    'version' => $awsConfig['version'] ?? 'latest',
                ]),
                'assume_role_params' => $clientConfig['assume_role_params'],
            ]);
            unset($clientConfig['assume_role_params']);
            $provider = CredentialProvider::memoize($assumeRoleCredentials);
            $clientConfig['credentials'] = $provider;
        }
        return new DynamoDbClient(array_merge($awsConfig, $clientConfig));
    }

    private function getConfig(ContainerInterface $container): array
    {
        if (! empty($this->clientConfig)) {
            return $this->clientConfig;
        }

        if (! $container->has('config')) {
            $this->clientConfig = [];
            return $this->clientConfig;
        }

        $config = $container->get('config');

        if (! isset($config['dynamo']) || ! is_array($config['dynamo'])) {
            $this->clientConfig = [];
            return $this->clientConfig;
        }

        $config = $config['dynamo']['client'];

        if (! isset($config['instances']) || ! is_array($config['instances'])) {
            $this->clientConfig = [];
            return $this->clientConfig;
        }

        $this->clientConfig = $config['instances'];

        return $this->clientConfig;
    }
}
