# Akisroc

## Setup

### Development environment

Building and serving locally dev project requires:
- [gosu](https://github.com/tianon/gosu)
- [docker-compose](https://docs.docker.com/compose)

```bash
# Build the project
make dev

# Start the application
make start

# Test the application
make test
```

### Production build

The production build requires:
- [php7.4](https://www.php.net)
- [composer](https://getcomposer.org)
- [npm](https://www.npmjs.com)

```bash
make prod
```
