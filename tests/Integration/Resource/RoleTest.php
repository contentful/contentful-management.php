<?php

/**
 * This file is part of the contentful-management.php package.
 *
 * @copyright 2015-2018 Contentful GmbH
 * @license   MIT
 */
declare(strict_types=1);

namespace Contentful\Tests\Management\Integration\Resource;

use Contentful\Management\ResourceBuilder;
use Contentful\Tests\Management\BaseTestCase;

class RoleTest extends BaseTestCase
{
    /**
     * @expectedException        \RuntimeException
     * @expectedExceptionMessage Trying to build a constraint object using invalid key "invalidKey".
     */
    public function testInvalidCreation()
    {
        $builder = new ResourceBuilder();

        $data = [
          'sys' => [
            'type' => 'Role',
          ],
          'name' => 'Custom role',
          'description' => 'This is a custom test role',
          'permissions' => [
              'ContentDelivery' => ['read', 'manage'],
              'ContentModel' => ['read'],
              'Settings' => 'all',
          ],
          'policies' => [
              [
                  'effect' => 'allow',
                  'actions' => 'all',
                  'constraint' => [
                      'invalidKey' => [],
                  ],
              ],
          ],
        ];

        $builder->build($data);
    }
}
