Kalories task for MotorK

If you dont want to use Docker skip step 2.

1. Clone repository
2. cd `/path/to/kalories/docker` and run `docker-compose up` or `docker-compose up -d` to demonize. Then run `docker exec -t -i kl_web /bin/bash` to access web(php) container.
3. First run `php bin/console doctrine:migrations:migrate` to apply migrations. Then you must apply fixtures by running the command `php bin/console doctrine:fixtures:load`, thats it! You got 3 users and dummy meals!
4. Then open `http://127.0.0.1/` and test all of this. Or you can edit `/etc/hosts` file by adding `kaloris.dev www.kalories.dev` after `localhost` inline.


If you want to change/update frontend files such as JS or CSS use Webpack Encore. 
- Do what you need in /app/assets
- Then run commands:
     `yarn run encore dev` - to build with dev comments and annotations
     `yarn run encore dev --watch` - to rebuild after every changes in assets
     `yarn run encore production` - build prodaction versions
     
P.S. You must have NPM and YARN in your system.  (Or extend docker. TODO)
