<?php

// You can include this file in your code or simply copy/paste it
// for configuring the active ResourceBuilder object
$builder->setDataMapperMatcher('Entry', function (array $data) {
    switch ($data['sys']['contentType']['sys']['id']) {
        case 'blogPost':
            return \Contentful\Tests\Management\Fixtures\Integration\CodeGenerator\Mapper\BlogPostMapper::class;
        case 'author':
            return \Contentful\Tests\Management\Fixtures\Integration\CodeGenerator\Mapper\AuthorMapper::class;
    }
});
