<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\PaymentAttempt;
use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderPreviewController extends AbstractController
{
    /**
     * @Route("/order/preview/{order_info}", name="order_preview")
     * @param $order_info
     * @param Request $request
     * @return Response
     */
    public function index($order_info, Request $request)
    {
        [$product_id, $customer_id, $order_id, $payment_method_id] = explode("-", $order_info);

        $product = $this->getDoctrine()
            ->getRepository(Products::class)
            ->find($product_id);

        $order = $this->getDoctrine()
            ->getRepository(Orders::class)
            ->find($order_id);

        $customer_fullname = $order->getCustomerName();

        $server_url = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $error_return_url = $server_url . '/payment/response/error';
        $source_url = $server_url . '/payment/attempt';
        $success_return_url = $server_url. '/payment/response/exito';
        $external_reference = $order_info;

        $payment_attempt = new PaymentAttempt();

        $payment_attempt_button = $this->createFormBuilder($payment_attempt)
            //->setAction($this->generateUrl('redirect_placetopay'))
            ->setMethod('POST')
            ->add('error_return_url', HiddenType::class, array(
                'data' => $error_return_url
            ))
            ->add('source_url', HiddenType::class, array(
                'data' => $source_url
            ))
            ->add('success_return_url', HiddenType::class, array(
                'data' => $success_return_url
            ))
            ->add('external_reference', HiddenType::class, array(
                'data' => $external_reference
            ))
            ->add('order_id', HiddenType::class, array(
                'data' => $order_id
            ))
            ->add('customer_id', HiddenType::class, array(
                'data' => $customer_id
            ))
            ->add('payment_method_id', HiddenType::class, array(
                'data' => $payment_method_id
            ))
            ->add('submitPayment', SubmitType::class, array(
                'label' => 'Pagar',
                'attr' => array(
                    'class' => 'btn btn-main btn-lg mt-2'
                )
            ))
            ->getForm();

        $payment_attempt_button->handleRequest($request);

        if($payment_attempt_button->isSubmitted() && $payment_attempt_button->isValid())
        {
            $payment_attempt_info = $payment_attempt_button->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($payment_attempt_info);
            $entityManager->flush();

            return $this->redirectToRoute('payment_attempt', array(
                'order_info' => $order_info
            ));
        }

        return $this->render('order_preview/index.html.twig', [
            'product' => $product,
            'form' => $payment_attempt_button->createView(),
            'customer_fullname' => $customer_fullname
        ]);
    }
}
