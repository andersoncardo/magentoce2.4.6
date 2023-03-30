<?php
declare(strict_types=1);

namespace Cardoso\CustomerExtension\Model;

use Magento\Framework\Logger\Monolog;
use Psr\Log\LoggerInterface;

class Log
{
    protected LoggerInterface|Monolog $logger;
    protected string $fileName = 'customer_registration.log';

    /**
     * Log constructor.
     *
     * @param Monolog $logger
     */
    public function __construct(Monolog $logger)
    {
        $this->logger = $logger;
        $this->logger->pushHandler(new \Monolog\Handler\StreamHandler(BP . '/var/log/' . $this->fileName));
    }

    /**
     * @param array $data
     * @param string $type
     * @param string $message
     */
    public function log(string $message, array $data, string $type = 'info'): void
    {
        if ($type == 'error') {
            $this->logger->error($message . json_encode($data));
        }
        $this->logger->info($message . json_encode($data));
    }
}
