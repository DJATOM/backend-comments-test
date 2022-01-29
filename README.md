## Comments API with simple front-end
### How to deploy
* `git clone https://github.com/DJATOM/backend-comments-test.git`
* `cd ./backend-comments-test`
* `[sudo] docker-compose up -d`
* `cp .env.example .env` and replace `DB_HOST=127.0.0.1` with `DB_HOST=mysql`
* change `DB_USERNAME` and `DB_PASSWORD` to some meaningful values
* go to php container (`[sudo] docker exec -it php sh`)
* install project requirements (`composer install`)
* link storage with public (`php artisan storage:link`)
* run migrations (`php artisan migrate [--seed]`)
* run tests (`php artisan test`)
