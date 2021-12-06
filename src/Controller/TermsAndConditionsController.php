<?php

namespace App\Controller;


use App\Entity\Order\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TermsAndConditionsController extends AbstractController
{

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        return $this->render('@SyliusShop/TermsAndConditions/index.html.twig');
    }

}
