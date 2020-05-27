init:
	@docker-compose up -d
	@docker-compose exec fpm composer install --ignore-platform-reqs --no-ansi --no-interaction
	@docker-compose exec fpm mkdir -p /app/apps/exercise/var/log
	@docker-compose exec fpm touch /app/apps/exercise/var/log/supervisor_stdout.log
	@docker-compose exec fpm touch /app/apps/exercise/var/log/supervisor_stderr.log
	@docker-compose exec fpm supervisord --configuration /etc/supervisor/conf.d/supervisord.conf

test:
	@docker exec check-it-out-fpm make run-tests

run-tests:
	./vendor/bin/phpunit
	./vendor/bin/behat -p exercise --format=progress -v
