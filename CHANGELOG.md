# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),  
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

_(nothing yet)_

## [1.1.0] - 2025-09-15

### Added
- Introduced enterprise modular support with new services: **Communication**, **Config**, **Messaging**, **ModuleMetrics**, **Security**.
- Added **Facades** for new services.
- Bootstrapped `tests/` directory for unit & integration coverage.
- Added DevOps, Analyze, DevTools, Docs, and Upgrade **command stubs**.
- Added new configuration files for **communication**, **metrics**, and **security**.

### Changed
- Refactored module generator commands for consistent namespace + filesystem handling.
- Updated `MakeAction`, `MakeCastCommand`, `MakeChannel`, `MakeInterfaceCommand` to display **final namespace + path** after generation.
- Reordered generator command signatures to follow `{name} {module}` convention.
- Repository **interfaces** are now generated under `Modules/<Module>/src/Repositories/Interfaces` (instead of `Contracts`).
- Standardized stub placeholders across all stubs (`action.stub`, `cast.stub`, `channel.stub`, `interface.stub`, etc.).
- Refactored commands under **Actions**, **Database**, **Publish**, and **Make** to follow consistent conventions.
- Cleaned up `config.php`, `core.php`, and `marketplace.php` for naming alignment.
- Updated `composer.json` and `README.md` to reflect the new structure.

### Fixed
- Corrected directory creation issues where files were being nested under unintended paths (e.g., `Modules/UserManagement/Email/src/*`).
- Improved error handling for **missing stubs**, **duplicate class detection**, and **directory creation**.

### Removed
- Deleted redundant/legacy generator commands:
  - `ComponentView`
  - `Model`
  - `Repository`
  - `Resource`
  - `Service`

---

## [1.0.1] - 2025-07-31

### Fixed
- Fixed case sensitivity issues in file paths and namespace resolution to ensure cross-platform compatibility on Linux and Windows.
  - Replaced manual `mkdir()` with `File::ensureDirectoryExists()` to handle OS differences.
  - Ensured consistent class name formatting using `Str::studly()`.
  - Normalized paths for autoloading accuracy.

## [1.0.0-alpha] - 2025-07-31

### Added
- Initial Commit: Modular Package System by [@Vishal-kumar007](https://github.com/Vishal-kumar007) in [#1](https://github.com/RCV-Technologies/laravel-module/pull/1)
- Updated README logo and removed commented/unnecessary code by [@vatsrajatkjha](https://github.com/vatsrajatkjha) in [#2](https://github.com/RCV-Technologies/laravel-module/pull/2)

### Contributors
- [@Vishal-kumar007](https://github.com/Vishal-kumar007) – First contribution
- [@vatsrajatkjha](https://github.com/vatsrajatkjha) – First contribution

**Full Changelog:** [v1.0.0-alpha commits »](https://github.com/RCV-Technologies/laravel-module/commits/v1.0.0-alpha)
