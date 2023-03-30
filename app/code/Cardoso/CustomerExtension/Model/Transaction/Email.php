<?php
declare(strict_types=1);

namespace Cardoso\CustomerExtension\Model\Transaction;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;

class Email
{
    /**
     * Error email template configuration
     */
    private const XML_PATH_SUPPORT_TEMPLATE = 'emails_new_user_created_with_white_spaces_fixed';
    private const SUPPORT_EMAIL = 'trans_email/ident_support/email';
    private TransportBuilder $transportBuilder;
    private StateInterface $inlineTranslation;
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @throws MailException
     * @throws LocalizedException
     */
    public function sendCustomerEmail(CustomerInterface $customer): void
    {
        $this->inlineTranslation->suspend();
        $emailTemplate = self::XML_PATH_SUPPORT_TEMPLATE;
        $emailTo = $this->scopeConfig->getValue(
            self::SUPPORT_EMAIL,
            ScopeInterface::SCOPE_STORE,
            Store::DEFAULT_STORE_ID
        );

        $this->processEmail($emailTemplate, $customer, $emailTo);
    }

    /**
     * @param string $emailTemplate
     * @param CustomerInterface $customer
     * @param mixed $emailTo
     * @return void
     * @throws LocalizedException
     * @throws MailException
     */
    private function processEmail(string $emailTemplate, CustomerInterface $customer, mixed $emailTo): void
    {
        $transport = $this->transportBuilder
            ->setTemplateIdentifier($emailTemplate)
            ->setTemplateOptions([
                'area' => Area::AREA_FRONTEND,
                'store' => Store::DEFAULT_STORE_ID,
            ])
            ->setTemplateVars([
                'customerFirstname' => $customer->getFirstname(),
                'customerLastname' => $customer->getLastname(),
                'customerEmail' => $customer->getEmail(),
            ])
            ->setFromByScope(
                'general',
                Store::DEFAULT_STORE_ID
            )
            ->addTo($emailTo)
            ->getTransport();

        $transport->sendMessage();

        $this->inlineTranslation->resume();
    }
}
