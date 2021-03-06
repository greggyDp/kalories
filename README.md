Kalories task for MotorK

If you dont want to use Docker skip step 2.

1. Clone repository
2. cd  to `/path/to/kalories/docker` and run:  
     - `docker-compose up` or `docker-compose up -d` to demonize
     - `docker exec -t -i kl_web /bin/bash` to access web(php) container
     - `cd app` to access workdir
3. In root app folder(`path/to/kalories/app`) run: 
     - `composer install` to update all dependencies. 
     - `php bin/console doctrine:migrations:migrate` to apply migrations
     - `php bin/console doctrine:fixtures:load` to apply fixtures
4. Open `http://127.0.0.1:8888/` in your browser to access application. 

5. (OPTIONAL) If you want enable prod mode, just edit .env file in `/path/to/kalories/app/.env` (or `/app/.env` if use docker) by setting APP_ENV & APP_DEBUG variables.

You can edit `/etc/hosts` file by adding `kaloris.dev www.kalories.dev` after `localhost` inline.

If you want to change/update frontend asstets such as JS or CSS use Webpack Encore. 
- Do what you need in /app/assets
- Then run `npm install`
- Then use such commands to build assets:
      `./node_modules/.bin/encore dev` - to build with dev comments and annotations
      `./node_modules/.bin/encore dev --watch` - to rebuild after every changes in assets
      `./node_modules/.bin/encore production` - build prodaction versions
- Or with yarn commands:
     `yarn run encore dev` - to build with dev comments and annotations
     `yarn run encore dev --watch` - to rebuild after every changes in assets
     `yarn run encore production` - build prodaction versions
     
P.S. If you do not use docker you must have NPM and YARN in your system to work with frontend assets.
