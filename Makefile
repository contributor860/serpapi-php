# Variables
PHP_VERSION ?= 7.4

# Default target
all: install readme test

# Clean up the project
clean:
	rm -rf vendor/

# Install the necessary libraries and dependencies
install:
	composer install --prefer-dist --no-progress

# Run the tests
test:
	vendor/bin/phpunit -c phpunit.xml

readme:
	erb -T '-' README.md.erb > README.md
