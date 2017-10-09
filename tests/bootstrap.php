<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/End2EndTestCase.php';

use VCR\Event\BeforeRecordEvent;
use VCR\Request;
use VCR\VCR;
use VCR\VCREvents;

function cleanRequest(BeforeRecordEvent $event, $eventName)
{
    $request = $event->getRequest();
    // Remove the Authorization header to prevent leaking CMA tokens
    $request->removeHeader('Authorization');
}

/**
 * @param Request $request
 *
 * @return array
 */
function clean_headers_array(Request $request)
{
    return array_filter($request->getHeaders(), function ($value, $name) {
        if ($value == false) {
            return false;
        }

        if (strtolower($name) === 'user-agent' || strtolower($name) === 'x-contentful-user-agent') {
            return false;
        }

        // Since we omit the Authorization header from recordings we can't match on it
        if (strtolower($name) === 'authorization') {
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
        $first = clean_headers_array($first);
        $second = clean_headers_array($second);

        return $first == $second;
    })
    ->enableRequestMatchers(['method', 'url', 'query_string', 'host', 'body', 'post_fields', 'custom_headers']);

VCR::getEventDispatcher()->addListener(VCREvents::VCR_BEFORE_RECORD, 'cleanRequest');

VCR::turnOn();
VCR::turnOff();
