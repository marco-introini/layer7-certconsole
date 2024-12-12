check:
	./vendor/bin/phpstan analyse
	./vendor/bin/rector --dry-run
	./vendor/bin/pest

production_data:
	php artisan migrate:fresh --seed
	php artisan db:seed --class ProductionSeeder
	./run_artisan_with_proxy.sh import:private-keys
	./run_artisan_with_proxy.sh import:trusted-certs
	./run_artisan_with_proxy.sh import:user-certificates


fake_data:
	php artisan migrate:fresh --seed
	php artisan db:seed --class FakeDataSeeder
