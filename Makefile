check:
	./vendor/bin/phpstan analyse

production_test:
	php artisan migrate:fresh --seed
	php artisan db:seed --class ProductionSeeder
