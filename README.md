
# Yolk Logger

A simple PSR-3 logging library. Currently supports output to the standard PHP error log, files, stdout, stderr and syslog.

## Requirements

This library requires PHP 5.4 or later, the Yolk Contracts package (```gamernetwork/yolk-contracts```) and the PSR-3 reference logger (```psr/log```).

## Installation

It is installable and autoloadable via Composer as gamer-network/yolk-logger.

Alternatively, download a release or clone this repository, and add the \yolk\log and \Psr\Log namespaces to an autoloader.

## License

Yolk Logger is open-sourced software licensed under the MIT license

## Quick Start

```php
use yolk\log\LogLevel;

// create a factory
$f = new LoggerFactory();

// create some simple logs with default threshold (INFO)
$l = $f->create('php');
$l = $f->create('stderr');
$l = $f->create('stdout');
$l = $f->create('null');

// specify configuration options
$l = $f->create([
	'type' => 'file'
	'file' => '/var/log/php/myapp.log'
]);

$l = $f->create([
	'type'   => 'syslog'
	'prefix' => 'myapp'
]);

// specify a threshold
$l = $f->create([
	'type'      => 'stderr',
	'threshold' => LogLevel::INFO,
]);

// simple message
$l->warning('Ooops! Something went wrong');

// message with context
$l->info(
	"{user} logged in at {time}",
	[
		'user' => 'Gary',
		'time' => '2014-10-02 12:34:56',
	]
);
```
