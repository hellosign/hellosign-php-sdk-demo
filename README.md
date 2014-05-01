HelloSign PHP Demo App
=========================

Introduction
------------
This is a simple application integrated with HelloSign API using ZendSkeletonApplication & HelloSign PHP SDK.

Installation
------------

### Localhost setup

Download project using git clone

    git clone git://github.com/HelloFax/hellosign-php-sdk-demo
    
Move to project directory, install dependencies

    composer install

Add required environment variables by openning public/index.php, prepend these lines

    $_ENV['HELLOSIGN_API_KEY'] = <api_key>;
    $_ENV['HELLOSIGN_CLIENT_ID'] = '<client_id>;
    $_ENV['HELLOSIGN_CLIENT_SECRET'] = <client_secret>;

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root directory (requires php >= 5.4)

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network interfaces.

**Note:** The built-in CLI server is for development only.

### Heroku setup

Create a new heroku app and push the code to heroku, then config required environment variables
    
    heroku config:set HELLOSIGN_API_KEY=<api_key>
    heroku config:set HELLOSIGN_CLIENT_ID=<client_id>
    heroku config:set HELLOSIGN_CLIENT_SECRET=<client_secret>
