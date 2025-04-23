# cs463

## Abstract

my eyes are bleeding and it's 5am i'll write this later

## Why

such is life

## Development

The following steps can be used to configure a development environment for
Windows, Mac, and Linux hosts. These steps assume that you have administrator
access to the machine you'll be developing on, have cloned this repo, and
currently have the project's root as your working directory.

1. Install PHP 8.3 or higher
    - Windows: Install a thread-safe build from [windows.php.net](https://windows.php.net/download)
    - Mac: Install using [Homebrew](https://brew.sh)
    - Linux: Install using your distribution's repos
2. Install [Composer](https://getcomposer.org/download/)
3. Install [NPM](https://npmjs.com)
    - Windows/Mac: Follow the [Node.js Installation Guide](https://nodejs.org/en/download/)
    - Linux: Use your distribution's repos or follow the above
4. Install [Symfony CLI](https://symfony.com/download)
    - Windows users may have an easier time using the `amd64` binary
      rather than installing from [Scoop](https://scoop.sh/)
5. Configure your project environment
    - Example config - Place in project root as `.env.local`
      ```ini
      # Configure environment for development
      APP_ENV=dev
      APP_DEBUG=1
      
      # This can just be a UUID. Make sure to drop the dashes.
      APP_SECRET='abcdefghijklmnopqrstuvwxyz123456'
      
      # Doctrine DB
      DATABASE_DSN=sqlite://var/database.sqlite
      ```
6. Install PHP vendor files with `composer install`
7. Install TS vendor files with `npm install`
8. Create database with `php bin/console doctrine:database:create`
9. Create schema with `php bin/console doctrine:schema:update --force`
10. Compile TS & SCSS with `npm run build`
     - During active development, it may be preferable to run
       `npm run watch`, which will stay running and rebuild source
       files while you edit TypeScript or SCSS files.
11. Serve with `symfony serve`

## Dependencies / Thanks

The following frameworks, libraries, etc. are used across the application:

 - [Symfony](https://symfony.com): PHP Framework
 - [Bootstrap](https://getbootstrap.com): CSS Framework
 - [Roboto](https://github.com/googlefonts/roboto-2): Primary Typeface
 - [Leaflet](https://leafletjs.com): Map Rendering

## License

most of this code is ripped from uwu_directory. i'm not licensing
this at the moment because i am tired. do not steal this code!
