<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2017 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Management\Exception;

/**
 * InvalidProxyActionException class.
 */
class InvalidProxyActionException extends \LogicException
{
    /**
     * InvalidProxyActionException.
     *
     * @param string      $class
     * @param string      $method
     * @param object|null $object
     */
    public function __construct(string $class, string $method, $object = null)
    {
        $message = \sprintf(
            'Trying to perform invalid action "%s" on proxy "%s"',
            $method,
            $class
        );

        if ($object !== null) {
            $message .= \sprintf(
                ' with argument of class "%s"',
                \get_class($object)
            );
        }

        $message .= '.';

        parent::__construct($message);
    }
}
