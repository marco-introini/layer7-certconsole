check:
	./vendor/bin/phpstan analyse

production_data:
	php artisan migrate:fresh --seed
	php artisan db:seed --class ProductionSeeder
