<?php

namespace App\Service;

// App\Payment\Callback\ConfirmationPayment

use App\Entity\Order\Order;
use App\Entity\Payment\Payment;
use Monolog\Logger;

class OrderCompletionService
{

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function handleCompetitionEntry(Payment $payment) {
        dd($payment);
        // TODO: Create entries for order items
        // TODO: Review confirmation email
    }


}