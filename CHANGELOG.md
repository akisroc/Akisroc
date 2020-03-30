# CHANGELOG

## v0.3.0 - Unreleased

This version does not bring any big new feature. The project being
abandoned for more than two years, the work has essentially been focused
upon deep refactors and technical updates.

Akisroc is now under PHP 7.4 and Symfony 5, which required some amount of work.
The automated test workflow has also been fixed and improved, including,
among other things, the addition of static analysis tools like PHPStan
and PHPInsights.

### Added
- Accurate error messages on forms

### Changed
- Styles
- Nicer main menu

### Security
- Multiple dependencies have been updated, fixing some
serious vulnerabilities

## [v0.2.0](https://github.com/Adrien-H/Akisroc/releases/tag/v0.2.0) - 2020.03.23

### Added
- Changelog page
- Custom pagination service
- SSL certificate
- Domain name
- Changelog file

---

## [v0.1.1](https://github.com/Adrien-H/Akisroc/releases/tag/v0.1.1) - 2017.01.04

### Fixed
- Git Service correctly displaying current tag
- 500 error on new topic

----

## [v0.1.0](https://github.com/Adrien-H/Akisroc/releases/tag/v0.1.0) - 2017.01.03

### Added
- Protagonists
    - Display in RP posts
    - Security around protagonists (RP restrictions, etc.)
- Forum basics
    - Categories
    - Boards
    - Topics
    - Posts
    - New topic form
    - New post form
- User system
    - Session handling
    - Authentification form
    - Registration form
- Symfony 4 configurations
    - Travis
    - PHPUnit
    - MariaDB Docker container
    - DataFixtures
    - Assets
