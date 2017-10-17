<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management;

/**
 * ApiDateTime class.
 *
 * This class is used for easier conversion to a timestamp that works with Contentful.
 */
class ApiDateTime extends \DateTimeImmutable
{
    /**
     * Formats the string for an easier interoperability with Contentful.
     *
     * @return string
     */
    public function formatForJson(): string
    {
        $date = $this->setTimezone(new \DateTimeZone('Etc/UTC'));
        $result = $date->format('Y-m-d\TH:i:s');
        $milliseconds = floor($date->format('u') / 1000);

        if ($milliseconds > 0) {
            $result .= '.'.str_pad((string) $milliseconds, 3, '0', STR_PAD_LEFT);
        }

        return $result.'Z';
    }

    /**
     * Returns a string representation of the current object.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->formatForJson();
    }
}
