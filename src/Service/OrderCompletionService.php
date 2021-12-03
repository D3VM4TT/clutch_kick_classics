<?php

namespace App\Service;

// App\Payment\Callback\ConfirmationPayment

use App\EmailManager\NewEntryEmailManager;
use App\Entity\Order\Order;
use App\Entity\Order\OrderItem;
use App\Entity\Order\OrderItemUnit;
use App\Entity\Payment\Payment;
use Monolog\Logger;
use Sylius\Component\Order\Model\OrderInterface;

class OrderCompletionService
{

    /**
     * @var NewEntryEmailManager
     */
    private $newEntryEmailManager;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Logger $logger, NewEntryEmailManager $newEntryEmailManager)
    {
        $this->logger = $logger;
        $this->newEntryEmailManager = $newEntryEmailManager;
    }

    public function handleCompetitionEntry(Payment $payment) {

        $this->newEntryEmailManager->sendNewEntryEmail($payment->getOrder());
        $this->addEntryNumbersToOrder($payment->getOrder());

    }

    public function addEntryNumbersToOrder(OrderInterface $order) {
        // Assign a entry number to each OrderItemUnit associated to the specific order
        /** @var OrderItem $item */
        foreach ($order->getItems() as $item) {
            /** @var OrderItemUnit $unit */
            foreach ($item->getUnits() as $unit) {
                $unit->setEntryNumber('1234');
            }
        }
    }


}