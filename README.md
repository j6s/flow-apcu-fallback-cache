# Flow APCu Fallback Cache

This package provides a small wrapper around flows default `ApcuBackend` which falls back to a given other backend, if the `apcu` PHP extension is not installed.

![](./Resources/Private/Doc/time.png)

## Installation

```bash
$ composer require j6s/flow-apcu-fallback-cache
```

## Configuration

```yaml
Neos_Fusion_Content:
  frontend: Neos\Cache\Frontend\StringFrontend
  backend: J6s\ApcuFallbackCache\Backend
  backendOptions:
    fallback:
      backend: Neos\Cache\Backend\PdoBackend
      backendOptions:
        dataSourceName: 'sqlite:%FLOW_PATH_DATA%/Temporary/Neos_Fusion_Content.sqlite'
```

## Why not use `MultiBackend`?

Flow already ships with a `MultiBackend` that will initialize multiple cache backend and use the first that does not throw an error. In theory this backend functions very similarly to the backend provided in this package, however it will always initialize all backends, leading to slower response times since the Initialization to the `PdoBackend` (used in example above) tends to take about 100ms.
