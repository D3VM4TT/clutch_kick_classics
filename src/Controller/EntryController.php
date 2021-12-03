<?php

namespace App\Controller;


use App\Entity\Order\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class EntryController extends AbstractController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $order = $this->get('doctrine.orm.default_entity_manager')->getRepository(Order::class)->find(28);
        return $this->render('Email/emails/new_entry.html.twig', [
            'order' => $order,
            'channel' => $order->getChannel(),
            'localeCode' => $order->getLocaleCode(),
        ]);


    }

}
