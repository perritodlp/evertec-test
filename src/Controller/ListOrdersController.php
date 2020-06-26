<?php

namespace App\Controller;

use App\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ListOrdersController extends AbstractController
{
    /**
     * @Route("/list/orders", name="list_orders")
     */
    public function index()
    {
        $order = $this->getDoctrine()
            ->getRepository(Orders::class)
            ->findAll();

        return $this->render('list_orders/index.html.twig', [
            'order_list' => $order
        ]);
    }
}
