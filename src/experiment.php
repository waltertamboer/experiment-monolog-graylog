<?php

require __DIR__ . '/../vendor/autoload.php';

$config = include __DIR__ . '/../config/global.php';
if (is_file(__DIR__ . '/../config/local.php')) {
    $config = array_merge($config, include __DIR__ . '/../config/local.php');
}

switch ($config['transport']) {
    case 'udp':
        $transport = new \Gelf\Transport\UdpTransport($config['host'], $config['port']);
        break;

    case 'tcp':
        $transport = new \Gelf\Transport\TcpTransport($config['host'], $config['port']);
        break;

    default:
        throw new \RuntimeException('Invalid transport provided.');
}

$transport = new \Gelf\Transport\UdpTransport($config['host'], $config['port']);

$gelfHandler = new \Monolog\Handler\GelfHandler(new \Gelf\Publisher($transport));
$gelfHandler->setFormatter(new \Monolog\Formatter\GelfMessageFormatter($config['system-name']));

$logger = new Monolog\Logger('application', [
    new \Monolog\Handler\StreamHandler('php://output'),
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
