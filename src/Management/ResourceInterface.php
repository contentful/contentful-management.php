<?php
/**
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */

namespace Contentful\Management;

interface ResourceInterface extends \JsonSerializable
{
    public function getSystemProperties(): SystemProperties;
}
