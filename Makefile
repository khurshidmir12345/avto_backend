# Laravel Docker orqali buyruqlar
# Ishlatish: make migrate, make artisan CMD="queue:work", va hokazo

.PHONY: migrate artisan shell

migrate:
	docker compose exec app php artisan migrate --force

artisan:
	docker compose exec app php artisan $(CMD)

shell:
	docker compose exec app bash
