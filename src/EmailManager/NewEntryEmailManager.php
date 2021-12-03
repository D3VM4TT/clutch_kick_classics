<?php

namespace App\EmailManager;

use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class NewEntryEmailManager
{
    /** @var SenderInterface */
    private $emailSender;

    /** @var AvailabilityCheckerInterface */
    private $availabilityChecker;

    /** @var RepositoryInterface $adminUserRepository */
    private $adminUserRepository;

    public function __construct(
        SenderInterface $emailSender,
        AvailabilityCheckerInterface $availabilityChecker,
        RepositoryInterface $adminUserRepository
    ) {
        $this->emailSender = $emailSender;
        $this->availabilityChecker = $availabilityChecker;
        $this->adminUserRepository = $adminUserRepository;
    }

    public function sendNewEntryEmail(OrderInterface $order): void
    {
        $this->emailSender->send(
            'new_entry',
            [$order->getCustomer()->getEmail()],
            [
                'order' => $order,
                'channel' => $order->getChannel(),
                'localeCode' => $order->getLocaleCode(),
            ]
        );
    }
}