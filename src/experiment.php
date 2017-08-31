<?php

use Gelf\Publisher;
use Gelf\Transport\TcpTransport;
use Gelf\Transport\UdpTransport;
use Monolog\Formatter\GelfMessageFormatter;
use Monolog\Handler\GelfHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require __DIR__ . '/../vendor/autoload.php';

$config = include __DIR__ . '/../config/global.php';
if (is_file(__DIR__ . '/../config/local.php')) {
    $config = array_merge($config, include __DIR__ . '/../config/local.php');
}

switch ($config['transport']) {
    case 'udp':
        $transport = new UdpTransport($config['host'], $config['port']);
        break;

    case 'tcp':
        $transport = new TcpTransport($config['host'], $config['port']);
        break;

    default:
        throw new RuntimeException('Invalid transport provided.');
}

$gelfHandler = new GelfHandler(new Publisher($transport));
$gelfHandler->setFormatter(new GelfMessageFormatter($config['system-name']));

$logger = new Logger('application', [
    new StreamHandler('php://output'),
    $gelfHandler,
]);

$logger->debug('This is a debug message.');
$logger->info('This is a message.');
$logger->notice('This is a notice.');
$logger->warning('This is a warning.');
$logger->error('This is an error.');
$logger->critical('This is a critical message.');
$logger->alert('This is an alert.');
$logger->emergency('This is an emergency.');
