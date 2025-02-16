check:
	./vendor/bin/phpstan analyse
	./vendor/bin/rector --dry-run
	./vendor/bin/pest

fake_data:
	php artisan migrate:fresh --seed
	php artisan db:seed --class FakeDataSeeder
