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

/*

$graylogServer = (isset($argv[2])) ? $argv[2] : 'dry-autumn-2579-194.fodor.xyz';
$transport = new UdpTransport($graylogServer, 5555);
$publisher = new Publisher($transport);
$gelfHandler = new GelfHandler($publisher);
$log = new Logger('Lego');
$log->pushHandler($gelfHandler);
$log->addWarning('Warning: ' . $message);
$log->addError('Error: ' . $message);
$log->addInfo('Info: ' . $message);
$log->addDebug('Debug: ' . $message);
*/