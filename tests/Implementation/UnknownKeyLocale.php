<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Tests\Management\Implementation;

use Contentful\Management\Resource\Locale;

use function GuzzleHttp\json_encode as guzzle_json_encode;

class UnknownKeyLocale extends Locale
{
    public function asRequestBody(): string
    {
        $body = $this->jsonSerialize();

        unset($body['sys']);
        unset($body['default']);

        $body['unknownKey'] = 'unknownValue';

        return guzzle_json_encode((object) $body, \JSON_UNESCAPED_UNICODE);
    }
}
