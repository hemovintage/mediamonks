## Mediamonks PHP Test 
[![N|Solid](https://i2.wp.com/revistafortuna.com.mx/contenido/wp-content/uploads/2018/01/mediaMonks_logo.jpg?zoom=1&resize=151%2C95&ssl=1)](https://www.mediamonks.com/)
by José Alberto Pérez
hemovintage@gmail.com

### Requirements
- Git 2.7.4 
- Docker engine v1.13 or higher
- Docker compose v1.12 or higher
- Composer 1.6.4 
- Yarn 1.6.0

### Repository
```sh 
$ git clone https://github.com/hemovintage/mediamonks
```

### Folder Structure
```sh 
mediamonks
|-- phpdocker
|   |-- nginx
|   `-- php-fpm
`-- project
    |-- assets
    |   |-- css
    |   `-- js
    |-- bin
    |-- config
    |   |-- packages
    |   |   |-- dev
    |   |   |-- prod
    |   |   `-- test
    |   `-- routes
    |       `-- dev
    |-- node_modules
    |   `--[...]
    |-- public
    |   `-- build
    |       |-- fonts
    |       `-- images
    |-- src
    |   |-- Controller
    |   |-- DataFixtures
    |   |   `-- ORM
    |   |-- Entity
    |   |-- EventListener
    |   |   `-- Author
    |   |-- Form
    |   |-- Migrations
    |   `-- Repository
    |-- templates
    |   |-- admin
    |   |-- api
    |   `-- blog
    |-- tests
    |-- translations
    |-- var
    |   |-- cache
    |   |-- data
    |   `-- log
    `-- vendor
        `-- [...]

```

### Building Docker Environment
#### to build docker environment
```sh 
$ ~/mediamonks/docker-compose build
```
#### to start docker engine
```sh 
$ ~/mediamonks/docker-compose up -d
```
your access point will be: 
### http://localhost:8080/
_if you have any port issue (8080) you can change it from the file '~/mediamonks/docker-compose.yml'_ 

```sh 
      ports:
       - "8080:80"
``` 

### Recommendations #
* Run composer outside of the php container, as doing so would install all your dependencies owned by `root` within your vendor folder.
* Run commands (ie Symfony's console, or Laravel's artisan) straight inside of your container. You can easily open a shell as described above and do your thing from there.
* if you change 8080 port let know this to the administrator, because it will be necesary to change it too in the platform auth0.

### Symfony 4 Project
_it is necesary to have installed Composer._ 
in the '~/mediamonks/project' folder
to install all dependencies declared in composer.json run:
```sh 
$ ~/mediamonks/project/composer install
```
_in case you have permission issues running this app with logs, cache or updateing database (is not typical) you should fix it. Please run:_ 
```sh 
$ ~/mediamonks/project/sudo chmod 777 var/cache/ -R 
$ ~/mediamonks/project/sudo chmod 777 var/logs/ -R 
$ ~/mediamonks/project/sudo chmod 777 var/data/ -R 
```

copy or rename '.env.dist' file to '.env'

### SQLite : Database  & Fixtures
For any change to the database it will be necesary to run this lines. 
Most recent SQLite file (~/mediamonks/project/var/data/blog.sqlite) is updated directly from the models.

To create the configured database
```sh 
php bin/console doctrine:database:create
``` 
To execute (or dump) the SQL needed to update the database schema to match the current mapping metadata
```sh 
php bin/console doctrine:schema:update --force
```
To load data fixtures to your database from (~/mediamonks/project/src/DataFixtures/ORM/Fixtures.php)
```sh 
php bin/console doctrine:fixtures:load 
```
### Bootstrap & Assets
_If you do not have Yarn, a Javascript package manager, installed, you will need to install and configure this first. So go to their Installation page and follow the instructions for installing and configuring Yarn first. * -> https://yarnpkg.com/lang/en/docs/install/#debian-stable_

#### Compiling assets 
To install all or pending dependencies run in the project folder :
```sh 
yarn add sass-loader node-sass --dev
```
To compile for first time or last changes in the assets run: 
```sh
yarn run encore dev --watch
```
_this last command will watch our changes in our files, and auto-compile if it is necesary._

### Author user for the Admin

##### default_author_user : 'joseperez'
##### default_author_pass : 'FitzRoy1683'

#
#
# 
# Important Note:
_In case Composer, Bootstrap or Yarn were problematic (or you feel lazy) you could unzip **'~/mediamonks/project/full_project.zip'** with all vendors & assets in it._
