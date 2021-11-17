<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\OrderItemUnit as BaseOrderItemUnit;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_order_item_unit")
 */
class OrderItemUnit extends BaseOrderItemUnit
{
    /**
     * @ORM\Column(name="entry_number", type="string", nullable=true)
     */
    private $entryNumber;

    /**
     * @return mixed
     */
    public function getEntryNumber()
    {
        return $this->entryNumber;
    }

    /**
     * @param mixed $entryNumber
     */
    public function setEntryNumber($entryNumber): void
    {
        $this->entryNumber = $entryNumber;
    }
}
