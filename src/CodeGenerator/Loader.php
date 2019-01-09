<?php

/**
 * This file is part of the contentful/contentful-management package.
 *
 * @copyright 2015-2019 Contentful GmbH
 * @license   MIT
 */

declare(strict_types=1);

namespace Contentful\Management\CodeGenerator;

use Contentful\Management\Resource\ContentType;
use PhpParser\Node;

class Loader extends BaseCodeGenerator
{
    /**
     * {@inheritdoc}
     */
    public function generate(array $params): string
    {
        return $this->render(new Node\Expr\MethodCall(
            new Node\Expr\Variable('builder'),
            'setDataMapperMatcher',
            [
                new Node\Arg(new Node\Scalar\String_('Entry')),
                new Node\Arg($this->generateMatcherClosure(
                    $params['content_types'],
                    $params['namespace']
                )),
            ],
            $this->generateCommentAttributes(
                '// You can include this file in your code or simply copy/paste it'
                ."\n"
                .'// for configuring the active ResourceBuilder object'
            )
        ));
    }

    /**
     * Generates the following code.
     *
     * ```
     * function (array $data) {
     *     switch ({{ var }}) {
     *         {{ cases }}
     *     }
     * }
     * ```
     *
     * @param array  $contentTypes
     * @param string $namespace
     *
     * @return Node\Expr\Closure
     */
    private function generateMatcherClosure(array $contentTypes, string $namespace): Node\Expr\Closure
    {
        return new Node\Expr\Closure([
            'params' => [new Node\Param('data', \null, 'array')],
            'stmts' => [
                new Node\Stmt\Switch_(
                    $this->generateSwitchVar(),
                    $this->generateCases($contentTypes, $namespace)
                ),
            ],
        ]);
    }

    /**
     * Generates the following code.
     *
     * ```
     * $data['sys']['contentType']['sys']['id']
     * ```
     *
     * @return Node\Expr
     */
    private function generateSwitchVar(): Node\Expr
    {
        return new Node\Expr\ArrayDimFetch(
            new Node\Expr\ArrayDimFetch(
                new Node\Expr\ArrayDimFetch(
                    new Node\Expr\ArrayDimFetch(
                        new Node\Expr\Variable('data'),
                        new Node\Scalar\String_('sys')
                    ),
                    new Node\Scalar\String_('contentType')
                ),
                new Node\Scalar\String_('sys')
            ),
            new Node\Scalar\String_('id')
        );
    }

    /**
     * Generates the following code.
     *
     * ```
     * case 'contentType':
     *     return \Fully\Qualified\Class\Name::class;
     * ```
     *
     * @param ContentType[] $contentTypes
     * @param string        $namespace
     *
     * @return Node\Stmt\Case_[]
     */
    private function generateCases(array $contentTypes, string $namespace): array
    {
        $classParts = \array_filter(\explode('\\', $namespace));

        return \array_map(function (ContentType $contentType) use ($classParts) {
            $classParts[] = 'Mapper';
            $classParts[] = $this->convertToStudlyCaps($contentType->getId()).'Mapper';

            return new Node\Stmt\Case_(
                new Node\Scalar\String_($contentType->getId()),
                [
                    new Node\Stmt\Return_(
                        new Node\Expr\ClassConstFetch(
                            new Node\Name\FullyQualified($classParts),
                            'class'
                        )
                    ),
                ]
            );
        }, $contentTypes);
    }
}
