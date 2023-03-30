<?php
declare(strict_types=1);

namespace Cardoso\CustomerExtension\Plugin;

use Cardoso\CustomerExtension\Model\Log;
use Cardoso\CustomerExtension\Model\Transaction\Email;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;

class CustomerPlugin
{
    private Email $email;
    private Log $log;

    /**
     * @param Email $email
     * @param Log $log
     */
    public function __construct(
        Email $email,
        Log $log
    ) {
        $this->email = $email;
        $this->log = $log;
    }
    /**
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $customer
     * @param string|null $passwordHash
     * @return array
     */
    public function beforeSave(
        CustomerRepositoryInterface $subject,
        CustomerInterface $customer,
        string $passwordHash = null
    ): array {
        $firstnameReplaced = preg_replace('/\s+/', '', $customer->getFirstname());
        if ($customer->getFirstname() == $firstnameReplaced) {
            return [$customer, $passwordHash];
        }

        $customer->setFirstname($firstnameReplaced);
        $log =  [
                'date' => date('Y-m-d H:i:s'),
                'first_name' => $customer->getFirstname(),
                'last_name' => $customer->getLastname(),
                'email' => $customer->getEmail(),
            ];

        try {
            $this->email->sendCustomerEmail($customer);
            $this->log->log('Customer registration data:', $log);
        } catch (\Exception $exception) {
            $this->log->log($exception->getMessage(), $log);
        }
        return [$customer, $passwordHash];
    }
}
