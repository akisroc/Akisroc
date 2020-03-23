# Akisroc

## Setup
```bash
# Project root

# Install Composer dependencies
composer install

# Run Docker images
docker-compose run

# Copy local .env file then configure it to your needs
cp .env.dist .env

# Generate database
php bin/console doctrine:schema:create

# Create local file for admin crendentials (adapt to your needs)
echo $'Admin\nadmin@admin.fr\nAdmin' >src/DataFixtures/Prod/.apwd

# Load fixtures
php bin/console doctrine:fixtures:load --no-interaction

# In prod environment, you might not want to leave this here
rm src/DataFixtures/Prod/.apwd

# Init and update submodules
git submodule update --init

# Install and build NPM dependencies
npm install
npm run dev
```
