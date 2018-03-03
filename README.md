Kalories task for MotorK

If you dont want to use Docker skip step 2.

1. Clone repository
2. cd  to `/path/to/kalories/docker` and run:  
     - `docker-compose up` or `docker-compose up -d` to demonize
     - `docker exec -t -i kl_web /bin/bash` to access web(php) container
3. In root app folder(`path/to/kalories/app`) run: 
     - `composer install` to update all dependencies. 
     - `php bin/console doctrine:migrations:migrate` to apply migrations
     - `php bin/console doctrine:fixtures:load` to apply fixtures
4. Open `http://127.0.0.1/` in your bowser to access application. 

You can edit `/etc/hosts` file by adding `kaloris.dev www.kalories.dev` after `localhost` inline.

If you want to change/update frontend asstets such as JS or CSS use Webpack Encore. 
- Do what you need in /app/assets
- Then run commands:
     `yarn run encore dev` - to build with dev comments and annotations
     `yarn run encore dev --watch` - to rebuild after every changes in assets
     `yarn run encore production` - build prodaction versions
     
P.S. You must have NPM and YARN in your system.  (Or extend docker. TODO)
