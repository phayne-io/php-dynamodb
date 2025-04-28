<?php

/**
 * This file is part of phayne-io/php-dynamodb and is proprietary and confidential.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 * @see       https://github.com/phayne-io/php-dynamodb for the canonical source repository
 * @copyright Copyright (c) 2024-2025 Phayne Limited. (https://phayne.io)
 */

declare(strict_types=1);

namespace Phayne\DynamoDB\Enum;

/**
 * Enum StreamViewType
 *
 * @package Phayne\DynamoDB\Enum
 */
enum StreamViewType: string
{
    /**
     * The entire item, as it appears after it was modified, is written to the stream.
     */
    case NEW_IMAGE = 'NEW_IMAGE';

    /**
     * The entire item, as it appeared before it was modified, is written to the stream.
     */
    case OLD_IMAGE = 'OLD_IMAGE';

    /**
     * Both the new and the old item images of the item are written to the stream.
     */
    case NEW_AND_OLD_IMAGES = 'NEW_AND_OLD_IMAGES';

    /**
     * Only the key attributes of the modified item are written to the stream.
     */
    case KEYS_ONLY = 'KEYS_ONLY';
}
