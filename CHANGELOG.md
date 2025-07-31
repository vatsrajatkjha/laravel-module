# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),  
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

_(nothing yet)_

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

