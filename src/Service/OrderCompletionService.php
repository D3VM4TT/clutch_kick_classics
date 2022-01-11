<?php

namespace App\Service;

// App\Payment\Callback\ConfirmationPayment

use App\EmailManager\NewEntryEmailManager;
use App\Entity\Order\OrderItem;
use App\Entity\Order\OrderItemUnit;
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
     * @var EntityManager
     */
    private $em;

    public function __construct(NewEntryEmailManager $newEntryEmailManager, EntityManager $em)
    {
        $this->newEntryEmailManager = $newEntryEmailManager;
        $this->em = $em;
    }

    public function handleCompetitionEntry(OrderInterface $order) {
        $this->addEntryNumbersToOrder($order);
        $this->newEntryEmailManager->sendNewEntryEmail($order);
    }

    public function addEntryNumbersToOrder(OrderInterface $order) {
        // Assign a entry number to each OrderItemUnit associated to the specific order
        /** @var OrderItem $item */
        foreach ($order->getItems() as $item) {
            $iteration = 0;
            /** @var OrderItemUnit $unit */
            foreach ($item->getUnits() as $unit) {
                if ($unit->getEntryNumber() > 0) {
                    continue;
                }
                $iteration++;
                $unit->setEntryNumber($this->generateEntryNumber($iteration));
                $this->em->persist($unit);
                $this->em->flush();
            }
        }
    }

    /**
     * This function gets the highest entry number from the DB & adds 1 to it.
     * If there are no entry numbers in the DB, the entry number will be set to 1.
     *
     */
    public function generateEntryNumber($iteration) {
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