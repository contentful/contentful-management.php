<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

use VCR\Event\BeforeRecordEvent;
use VCR\Request;
use VCR\VCR;
use VCR\VCREvents;

/**
 * @param Request $request
 *
 * @return array
 */
function clean_headers_array(Request $request)
{
    return \array_filter($request->getHeaders(), function ($value, $name) {
        if (false === $value) {
            return false;
        }

        if ('user-agent' === \mb_strtolower($name) || 'x-contentful-user-agent' === \mb_strtolower($name)) {
            return false;
        }

        // Since we omit the Authorization header from recordings we can't match on it
        if ('authorization' === \mb_strtolower($name)) {
            return false;
        }

        return true;
    }, ARRAY_FILTER_USE_BOTH);
}

// The VCR needs to be loaded before the Client is loaded for the first time or it will fail
VCR::configure()
    ->setMode('once')
    ->setStorage('json')
    ->setCassettePath('tests/Recordings')
    ->addRequestMatcher('custom_headers', function (Request $first, Request $second) {
        return clean_headers_array($first) === clean_headers_array($second);
    })
    ->enableRequestMatchers(['method', 'url', 'query_string', 'host', 'body', 'post_fields', 'custom_headers']);

VCR::getEventDispatcher()
    ->addListener(VCREvents::VCR_BEFORE_RECORD, function (BeforeRecordEvent $event) {
        $request = $event->getRequest();
        // Remove the Authorization header to prevent leaking CMA tokens
        $request->removeHeader('Authorization');
    });

VCR::turnOn();
VCR::turnOff();
