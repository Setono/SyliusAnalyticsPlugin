# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [2.0.x] - 2021-04-15

### Added
- Support for Symfony 5.x (<5.3)
- Danish translations
- French translations

### Changed
- Removed usage of Safe library for json encode/decode, now using native PHP constant `JSON_THROW_ON_ERROR`
- Performance: `PropertyContext` now caches the properties
- Now using TagBag library instead of TagBagBundle (from Setono as well)
- Add a tag for each property in the library subscriber (previously only one tag were added containing all properties)
- Added psalm to the CI, the codebase has been updated and is now more type-safe

### Removed
- Support for Symfony 4.1 and older
- Option `resources.property.classes.interface` does not exists anymore
