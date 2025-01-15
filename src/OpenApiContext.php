<?php

namespace Dedoc\Scramble;

use Dedoc\Scramble\Support\Generator\OpenApi;

/** @internal */
class OpenApiContext
{
    public function __construct(
        public readonly OpenApi $openApi,
        public readonly GeneratorConfig $config,
        public ContextReferences $references = new ContextReferences,
    ) {}
}