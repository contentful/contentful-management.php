<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2025 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

\date_default_timezone_set('UTC');

use VCR\Event\BeforeRecordEvent;
use VCR\Request;
use VCR\VCR;
use VCR\VCREvents;

if ('api-coverage' === \getenv('CONTENTFUL_PHP_MANAGEMENT_SDK_ENV')) {
    return;
}

/**
 * @return array
 */
function clean_headers_array(Request $request)
{
    return \array_filter($request->getHeaders(), function ($value, $name) {
        if (false === $value) {
            return false;
        }

        $name_lower = \mb_strtolower($name);

        return !\in_array($name_lower, [
            'user-agent',
            'x-contentful-user-agent',
            'authorization',
            'expect',
        ], true);
    }, \ARRAY_FILTER_USE_BOTH);
}

// The VCR needs to be loaded before the Client is loaded for the first time or it will fail
VCR::configure()
    ->setMode(VCR::MODE_ONCE)
    ->setStorage('json')
    ->setCassettePath('tests/Recordings')
    ->addRequestMatcher('custom_headers', function (Request $first, Request $second) {
        $a = clean_headers_array($first);
        $b = clean_headers_array($second);
        $result = $a === $b;
        if ($result) {
            return $result;
        }
        var_dump($a);
        var_dump($b);

        return $result;
    })
    ->enableRequestMatchers(['method', 'url', 'query_string', 'host', 'body', 'post_fields', 'custom_headers'])
;

// Remove the Authorization header to prevent leaking CMA tokens
VCR::getEventDispatcher()
    // ->addListener(VCREvents::)
    ->addListener(VCREvents::VCR_BEFORE_RECORD, function (BeforeRecordEvent $event) {
        $event->getRequest()->removeHeader('Authorization');
    })
;

VCR::turnOn();
VCR::turnOff();
