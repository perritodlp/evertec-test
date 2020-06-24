<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function homepage()
    {
        return $this->render('order/homepage.html.twig');
    }

    /**
     * @Route("/order-preview/{product_id}")
     * @param $product_id
     * @return Response
     */
    public function orderPreview($product_id)
    {
        return $this->render('order/order-preview.html.twig', [
            'product_id' => $product_id
        ]);
    }
}