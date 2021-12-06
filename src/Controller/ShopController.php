<?php

namespace App\Controller;


use App\Entity\Order\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ShopController extends AbstractController
{

    public function termsAndConditionsAction(Request $request): Response
    {
        return $this->render('@SyliusShop/TermsAndConditions/index.html.twig', );
    }

    public function PrivacyPolicyAction(Request $request): Response
    {
        return $this->render('@SyliusShop/PrivacyPolicy/index.html.twig');
    }

}
