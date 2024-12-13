refresh-bdd:
	@echo "Refreshing bdd.."
	@php artisan migrate:refresh --seed

run-test:
	@echo "Running test ..."
	@./vendor/bin/pest