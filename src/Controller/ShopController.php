<?php

namespace App\Controller;


use App\Entity\Order\Order;
use Sylius\Component\Core\OrderCheckoutTransitions;
use Sylius\Component\Core\OrderPaymentTransitions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ShopController extends AbstractController
{

    public function termsAndConditionsAction(Request $request): Response
    {
        return $this->render('@SyliusShop/TermsAndConditions/index.html.twig', );
    }

    public function privacyPolicyAction(Request $request): Response
    {
        return $this->render('@SyliusShop/PrivacyPolicy/index.html.twig');
    }

    public function theCarAction(Request $request): Response
    {
        return $this->render('@SyliusShop/TheCar/show.html.twig');
    }

    public function processPaymentAction($order_id, Request $request): Response
    {
        // get the current order
        $order = $this->get('doctrine.orm.default_entity_manager')->getRepository(Order::class)->find($order_id);
        $stateMachineFactory = $this->container->get('sm.factory');
        $orderCheckoutStateMachine = $stateMachineFactory->get($order, OrderCheckoutTransitions::GRAPH);

        // ORDER CHECKOUT PROCESS

        // --- 1. Select Payment ---
        $shippingMethod = $this->container->get('sylius.repository.shipping_method')->findOneByCode('UPS');

        // Shipments are a Collection, so even though you have one Shipment by default you have to iterate over them
        foreach ($order->getShipments() as $shipment) {
            $shipment->setMethod($shippingMethod);
        }

        $orderCheckoutStateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_SHIPPING);
        $this->container->get('sylius.manager.order')->flush();

        // --- 2. Select Payment ---
        // Let's assume that you have a method with code 'paypal' configured
        $paymentMethod = $this->container->get('sylius.repository.payment_method')->findOneByCode('payfast_payment');

        // Payments are a Collection, so even though you have one Payment by default you have to iterate over them
        foreach ($order->getPayments() as $payment) {
            $payment->setMethod($paymentMethod);
        }

        $orderCheckoutStateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_PAYMENT);
        $this->container->get('sylius.manager.order')->flush();

        // --- 3. Complete the Order ---
        $orderCheckoutStateMachine->apply(OrderCheckoutTransitions::TRANSITION_COMPLETE);
        $this->container->get('sylius.manager.order')->flush();

        // --- 4. Pay for the order ---
        $orderPaymentStateMachine = $stateMachineFactory->get($order, OrderPaymentTransitions::GRAPH);
//        $orderPaymentStateMachine->apply(OrderPaymentTransitions::TRANSITION_REQUEST_PAYMENT);
        $orderPaymentStateMachine->apply(OrderPaymentTransitions::TRANSITION_PAY);
        $this->container->get('sylius.manager.order')->flush();

        $request->getSession()->set('sylius_order_id', $order->getId());

        // redirect to thank you route
        // TODO: get redirect to work
        return $this->redirectToRoute('sylius_shop_order_thank_you');

    }

    public function viewEmailAction(Request $request): Response
    {
        $order = $this->get('doctrine.orm.default_entity_manager')->getRepository(Order::class)->find(55);

        return $this->render('Email/emails/new_entry.html.twig', [
            'order' => $order,
            'channel' => $order->getChannel(),
            'localeCode' => $order->getLocaleCode(),
        ]);
    }

}
