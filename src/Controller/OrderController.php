<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController
{
    /**
     * @Route("/")
     */
    public function homepage()
    {
        return new Response('Hello fucking World!');
    }

    /**
     * @Route("/order-preview/{product_id}")
     * @param $product_id
     * @return Response
     */
    public function orderPreview($product_id)
    {
        return new Response(sprintf('Order preview "%s": ', $product_id));
    }
}