# Architecture: container-interop

## Purpose

A compatibility shim providing `Interop\Container` interfaces that delegate to PSR-11 (`Psr\Container`) interfaces. This allows legacy code that depends on `container-interop` to coexist with PSR-11-compatible containers.

## Directory Structure

```
src/
  Interop/
    Container/
      Container_Interface.php         — Extends Psr\Container\ContainerInterface (identity alias)
      Exception/
        Container_Exception.php       — Extends Psr\Container\ContainerExceptionInterface
        Not_Found_Exception.php       — Extends Container_Exception + Psr\Container\NotFoundExceptionInterface
```

## Key Design Decisions

- **All interfaces are pure aliases** — no added methods; they simply re-export PSR-11 under the `Interop\Container` namespace so older packages resolving `container-interop` still satisfy type checks.
- **No concrete implementations** — this package is intentionally interface-only; implementations live in container packages (e.g., PHP-DI, Symfony DI).
- **Backward compatibility only** — new code should depend on `psr/container` directly.

## Extension Points

None. This package is a frozen compatibility bridge.

## Dependency Flow

```
container-interop/Container_Interface
    └── Psr\Container\ContainerInterface  (psr/container)

container-interop/Exception/Container_Exception
    └── Psr\Container\ContainerExceptionInterface

container-interop/Exception/Not_Found_Exception
    ├── Container_Exception
    └── Psr\Container\NotFoundExceptionInterface
```
