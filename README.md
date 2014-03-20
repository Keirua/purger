# Purger

TL;DR: Small app that performs purge requests on a set of URLs. 

Often, flushing a large set of urls requires either to restart varnish (thus destroying all those precious pages already in cache) or to perform a lot of queries of a large set of urls

## Usage

Install the required packages thanks to composer (https://getcomposer.org). You need to have a RabbitMQ server up and running.

    curl -sS https://getcomposer.org/installer | php
    php composer.phar install

Then, start a server

    ./console listen

Now, you can start sending urls to the flusher thanks to

    ./console flush filename

where filename is the location of a file containing the urls that you want to flush, one url on each line.

If you wannt to know how many urls still remain in the queue and have to be flushed, just run :

    ./console status

## Unit tests

Unit tests can be run using 

     ./vendor/bin/phpunit tests