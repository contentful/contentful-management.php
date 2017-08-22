<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/End2EndTestCase.php';

use VCR\Event\BeforeRecordEvent;

function cleanRequest(BeforeRecordEvent $event, $eventName)
{
    $request = $event->getRequest();
    // Remove the Authorization header to prevent leaking CMA tokens
    $request->removeHeader('Authorization');
}

/**
 * @param \VCR\Request $request
 *
 * @return array
 */
function clean_headers_array(\VCR\Request $request)
{
    return array_filter($request->getHeaders(), function ($value, $name) {
        if ($value == false) {
            return false;
        }

        if (strtolower($name) === 'user-agent' || strtolower($name) === 'x-contentful-user-agent') {
            return false;
        }

        // Since we omit the Authorization header from recordings we can't mach on it
        if (strtolower($name) === 'authorization') {
            return false;
        }

        return true;
    }, ARRAY_FILTER_USE_BOTH);
}

// The VCR needs to be loaded before the Client is loaded for the first time or it will fail
\VCR\VCR::configure()
    ->setMode('once')
    ->setStorage('json')
    ->setCassettePath('tests/recordings')
    ->addRequestMatcher('custom_headers', function (\VCR\Request $first, \VCR\Request $second) {
        $first = clean_headers_array($first);
        $second = clean_headers_array($second);

        return $first == $second;
    })
    ->enableRequestMatchers(['method', 'url', 'query_string', 'host', 'body', 'post_fields', 'custom_headers']);

\VCR\VCR::getEventDispatcher()->addListener(\VCR\VCREvents::VCR_BEFORE_RECORD, 'cleanRequest');

\VCR\VCR::turnOn();
\VCR\VCR::turnOff();
