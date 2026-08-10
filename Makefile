# Default target
all: install readme analyse test

# Clean up the project
clean:
	rm -rf vendor/

# Install the necessary libraries and dependencies
install:
	composer install --prefer-dist --no-progress

# Run static analysis
analyse:
	vendor/bin/phpstan analyse --no-progress --memory-limit=512M

# Run the tests
test:
	vendor/bin/phpunit -c phpunit.xml

# Generate README from ERB template
readme:
	erb -T '-' README.md.erb > README.md

# Run the demo scripts end to end
demo:
	@for file in demo/*.php; do \
		echo "running demo: $$file"; \
		php $$file || exit 1; \
	done
