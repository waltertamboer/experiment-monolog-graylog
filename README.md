# experiment-monolog-graylog

An experiment that tests using Monolog in combination with Graylog.

## Usage

Configure the configuration by copying `config/global.php` 
to `config/local.php` and entering the correct information.

Next install the composer dependencies, assuming Docker is used:

```bash
docker run --rm -it -v "$(pwd):/app" composer:latest install
```

And then run the scrip:

```bash
docker run --rm -it -v "$(pwd):/app" php:latest php -f /app/src/experiment.php
```
