run:
	@echo "------------------------> Running Server <------------------------"
	php artisan serve

migrate:
	php artisan migrate

migrate-status:
	php artisan migrate:status

migrate-reset:
	php artisan migrate:reset


migrate-refresh:
	php artisan migrate:refresh

clear-laravel:
	php artisan cache:clear
	
rd:
	npm run dev

install:
	@echo "Installing all system dependencies using apt-get"
	# rm -rf .env
	sudo bash install.sh
	#  sudo chmod 777 .env
	# php artisan config:clear
	# sudo php artisan key:generate
	# php artisan migrate

db:
	@echo "Options Database"
	sudo scripts/database.sh

mailserver:
	./tools/bin/mailhog &
	@echo "MailHog opened ..."


shell:
	php artisan tinker

options:
	@echo
	@echo ----------------------------------------------------------------------
	@echo "   >>>>>                 Scripts for PHP               <<<<<   "
	@echo ----------------------------------------------------------------------
	@echo
	@echo "   - install     SETTINGS=[PHP]    Install App and their dependencies"
	@echo "   - migrate     SETTINGS=[PHP]    migrate models for database"
	@echo "   - serve       SETTINGS=[PHP]    Serve project for development"
	@echo "   - mail_server SETTINGS=[PHP]    Open the Development Mail Server"
	@echo "   - shell       SETTINGS=[PHP]    Run laravel in shell mode for development"
	@echo "   - deps        SETTINGS=[PHP]    install packages for PHP"
	@echo
	@echo ----------------------------------------------------------------------

clean_mode:
	rm -rf node_modules
	rm -rf static/dist

lint:
	@npm run lint --silent

clear:
	php artisan cache:clear
	php artisan config:cache
	php artisan route:clear
	php artisan view:clear
	php artisan config:clear
restart:
	php artisan cache:clear
	php artisan route:clear
	php artisan view:clear
	php artisan config:clear
	php artisan config:cache
	php artisan serve
