<?php

namespace Dedoc\Scramble;

use Closure;
use Dedoc\Scramble\Configuration\ParametersExtractors;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class GeneratorConfig
{
    public function __construct(
        private array $config = [],
        private ?Closure $routeResolver = null,
        private array $afterOpenApiGenerated = [],
        public readonly ParametersExtractors $parametersExtractors = new ParametersExtractors,
    ) {}

    public function config(array $config)
    {
        $this->config = $config;

        return $this;
    }

    public function routes(?Closure $routeResolver = null)
    {
        if (count(func_get_args()) === 0) {
            return $this->routeResolver ?: $this->defaultRoutesFilter(...);
        }

        if ($routeResolver) {
            $this->routeResolver = $routeResolver;
        }

        return $this;
    }

    private function defaultRoutesFilter(Route $route)
    {
        $expectedDomain = $this->get('api_domain');

        return Str::startsWith($route->uri, $this->get('api_path', 'api'))
            && (! $expectedDomain || $route->getDomain() === $expectedDomain);
    }

    public function afterOpenApiGenerated(?callable $afterOpenApiGenerated = null)
    {
        if (count(func_get_args()) === 0) {
            return $this->afterOpenApiGenerated;
        }

        if ($afterOpenApiGenerated) {
            $this->afterOpenApiGenerated[] = $afterOpenApiGenerated;
        }

        return $this;
    }

    public function useConfig(array $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function withParametersExtractors(callable $callback): static
    {
        $callback($this->parametersExtractors);

        return $this;
    }

    public function get(string $key, mixed $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }
}
