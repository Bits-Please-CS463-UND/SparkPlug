# cs463

Term project for CS463 (Software Engineering) under Dr. Emanuel Grant at University
of North Dakota.

## Abstract

From the project description...

> This request for service is for the development of a motor vehicle remote 
> starter application that may be used on either a mobile device or a desktop 
> computer; you must select one platform for your project.

Required features include...
 - Internet-accessible
 - Display of Internet connection status
 - Audio and visual feedback
 - Driver management
 - Lock/unlock doors
 - Start/stop engine
 - Display internal/external temperature
 - Display fuel level
 - Activate/deactivate anti-theft alarm

## Reports

Over the course of development, multiple reports were required regarding
development status. These can be found in the [`/docs`](/docs) directory.

 - [Project Report 1 (`/docs/report_01.md`)](/docs/report_01.md)
 - [Project Report 2 (`/docs/report_02.md`)](/docs/report_02.md)

## Running

"Binaries" are shipped as OCI images via the GitHub Container Registry.
To run the application, install Docker on your system and run the following
command:

```shell
docker run --rm -p 8018:80 --pull always ghcr.io/bits-please-cs463-und/sparkplug:latest
```

Once started, the application will be available at `http://localhost:8018`.

## Development

The following steps can be used to configure a development environment for
Windows, Mac, and Linux hosts. These steps assume that you have administrator
access to the machine you'll be developing on, have cloned this repo, and
currently have the project's root as your working directory.

1. Install PHP 8.3 or higher
    - Windows: Install a thread-safe build from [windows.php.net](https://windows.php.net/download)
      - Additionally, the `zip` and `php_sqlite3.dll` extensions will need to
        be enabled in `php.ini`. Do so by adding or uncommenting the following to your `[PHP]` section:
        ```ini
        extension=zip
        extension=php_sqlite3.dll
        ```
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
      APP_SECRET='abcdef12345678900987654321fedcba'
      
      # Doctrine DB
      DATABASE_DSN=sqlite://%kernel.project_dir%/var/app.db
      ```
6. Install PHP vendor files with `composer install`
    - This step may fail if the `zip` extension is not enabled in `php.ini`.
7. Install TS vendor files with `npm install`
8. Create schema with `php bin/console doctrine:schema:update --force`
9. Compile TS & SCSS with `npm run build`
    - During active development, it may be preferable to run
      `npm run watch`, which will stay running and rebuild source
      files while you edit TypeScript or SCSS files.
10. Serve with `symfony serve`. Alternatively, if using PHPStorm, a run configuration
    is provided in the `/.run/` directory. PHPStorm should pick this up automatically.
    The page may be accessed at `localhost:8000`.

## Dependencies / Thanks

The following frameworks, libraries, etc. are used across the application:

 - [Symfony](https://symfony.com): PHP Framework
 - [Bootstrap](https://getbootstrap.com): CSS Framework
 - [Roboto](https://github.com/googlefonts/roboto-2): Primary Typeface
 - [Leaflet](https://leafletjs.com): Map Rendering

## License

This project is AGPL-licensed. Please abide by its terms if you use any or
all of this code in your own works.