PHPDocker
=========

Dependencies:

* Docker engine v1.13 or higher. Your OS provided package might be a little old, if you encounter problems, do upgrade. See [https://docs.docker.com/engine/installation](https://docs.docker.com/engine/installation)
* Docker compose v1.12 or higher. See [docs.docker.com/compose/install](https://docs.docker.com/compose/install/)

Once you're done, simply `cd` to your project and run `docker-compose up -d`. This will initialise and start all the containers, then leave them running in the background.

Service  |Address outside containers
Webserver|[localhost:8080](http://localhost:8080)

Service|Hostname|Port number
php-fpm|php-fpm |9000

# Recommendations #

* Run composer outside of the php container, as doing so would install all your dependencies owned by `root` within your vendor folder.
* Run commands (ie Symfony's console, or Laravel's artisan) straight inside of your container. You can easily open a shell as described above and do your thing from there.