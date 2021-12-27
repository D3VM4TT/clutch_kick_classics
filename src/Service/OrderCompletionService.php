<?php

namespace App\Service;

// App\Payment\Callback\ConfirmationPayment

use App\EmailManager\NewEntryEmailManager;
use App\Entity\Order\Order;
use App\Entity\Order\OrderItem;
use App\Entity\Order\OrderItemUnit;
use App\Entity\Payment\Payment;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
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

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(Logger $logger, NewEntryEmailManager $newEntryEmailManager, EntityManager $em)
    {
        $this->logger = $logger;
        $this->newEntryEmailManager = $newEntryEmailManager;
        $this->em = $em;
    }

    public function handleCompetitionEntry(OrderInterface $order) {
        $this->newEntryEmailManager->sendNewEntryEmail($order);
        $this->addEntryNumbersToOrder($order);
    }

    public function addEntryNumbersToOrder(OrderInterface $order) {
        // Assign a entry number to each OrderItemUnit associated to the specific order
        /** @var OrderItem $item */
        foreach ($order->getItems() as $item) {
            $iteration = 0;
            /** @var OrderItemUnit $unit */
            foreach ($item->getUnits() as $unit) {
                $iteration++;
                $unit->setEntryNumber($this->generateEntryNumber($unit, $iteration));
            }
        }
    }

    /**
     * This function gets the highest entry number from the DB & adds 1 to it.
     * If there are no entry numbers in the DB, the entry number will be set to 1.
     *
     * @param OrderItemUnitInterface $orderItemUnit
     * @return int|mixed
     */
    public function generateEntryNumber(OrderItemUnitInterface $orderItemUnit, $iteration) {
        $entryNumber = 1;

        // get the highest entry number & add 1 to it;
        $query = $this->em->getRepository(OrderItemUnit::class)->createQueryBuilder('s');
        $query->select('s, MAX(s.entryNumber) as entry_number');
        $highestEntryNumber = $query->getQuery()->getResult();

        if (!empty($highestEntryNumber)) {
            $entryNumber = $highestEntryNumber[0]['entry_number'] + $iteration;
        }

        return $entryNumber;
    }


}