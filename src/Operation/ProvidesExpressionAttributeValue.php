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

use Phayne\DynamoDB\Enum\Operator;
use UnexpectedValueException;

/**
 * Trait ProvidesExpressionAttributeValue
 *
 * @package Phayne\DynamoDB\Operation
 */
trait ProvidesExpressionAttributeValue
{
    /**
     * @var string The name of the desired expression field.
     */
    protected string $expressionFieldName = 'FilterExpression';

    /**
     * @var string The separator for expression fields in an expression.
     */
    protected string $expressionSeparator = ' and ';

    /**
     * @var string The expression.
     */
    protected string $expression = '';

    /**
     * @var array Values that can be substituted in an expression.
     */
    protected array $expressionAttributeValues = [];

    /**
     * Registers the expression with this object.
     *
     * @param array $data The filter expression data.
     * @return mixed This object.
     * @throws UnexpectedValueException Thrown when an invalid operator is provided.
     */
    final public function setExpression(array $data): static
    {
        $expressionArray = [];
        foreach ($data as $key => $options) {
            $expressionArray[] = Operator::parseExpression($options['operator'], $key);
            $this->addExpressionAttributeValue($key, $options['value']);
        }
        $this->expression = implode($this->expressionSeparator, $expressionArray);
        return $this;
    }

    /**
     * Adds an ExpressionAttributeValue to the request.
     *
     * @param string $key The attribute token.
     * @param mixed $value The attribute value.
     * @return static This object.
     */
    final public function addExpressionAttributeValue(string $key, mixed $value): static
    {
        $this->expressionAttributeValues[sprintf(':%s', $key)] = $this->marshaler->marshalValue($value);
        return $this;
    }

    public function toArray(): array
    {
        $operation = [];

        if (! empty($this->expression)) {
            $operation[$this->expressionFieldName] = $this->expression;
        }

        if (! empty($this->expressionAttributeValues)) {
            $operation['ExpressionAttributeValues'] = $this->expressionAttributeValues;
        }

        return $operation;
    }
}
