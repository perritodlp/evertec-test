<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\PaymentAttempt;
use App\Entity\PaymentResponse;
use App\Entity\Products;
use Dnetix\Redirection\PlacetoPay;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PaymentResponseController extends AbstractController
{
    /**
     * @Route("/payment/response/{order_info}", name="payment_response")
     * @param $order_info
     * @param Request $request
     * @return Response
     * @throws \Dnetix\Redirection\Exceptions\PlacetoPayException
     */
    public function index($order_info, Request $request)
    {
        [$product_id, $customer_id, $order_id, $payment_method_id] = explode("-", $order_info);
        $placetopay_api = $this->getParameter('placetopay_api');
        $error = false;
        $product = '';
        $customer = '';
        $status = '';

        // Sacamos datos del cliente que compra
        $customer = $this->getDoctrine()
            ->getRepository(Orders::class)
            ->find($order_id);

        // Sacamos datos del intento de pago
        $payment_attempt = $this->getDoctrine()
            ->getRepository(PaymentAttempt::class)
            ->find($order_id);

        $request_id = $payment_attempt->getRequestId();
        $payment_attempt_id = $payment_attempt->getId();

        try {
            $placetopay_login = $this->getParameter('placetopay_login');
            $placetopay_trankey = $this->getParameter('placetopay_trankey');

            $placetopay = new PlacetoPay([
                'login' => $placetopay_login,
                'tranKey' => $placetopay_trankey,
                'url' => $placetopay_api,
                'rest' => [
                    'timeout' => 45, // (optional) 15 by default
                    'connect_timeout' => 30, // (optional) 5 by default
                ]
            ]);

            $response = $placetopay->query($request_id);

            //dd($response);

            if ($response->isSuccessful()) {
                // In order to use the functions please refer to the RedirectInformation class
                if ($response->status()->isApproved()) {
                    // The payment has been approved
                    // This is additional information about it
                    $message = $response->status()->message();
                    $status = $response->status()->status();

                    // Sacamos datos del producto comprado
                    $product = $this->getDoctrine()
                        ->getRepository(Products::class)
                        ->find($product_id);

                } else {
                    $message = $response->status()->message();
                    $status = $response->status()->status();

                    $server_url = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

                    $error_return_url = $server_url . '/payment/response/error';
                    $source_url = $server_url . '/payment/attempt';
                    $success_return_url = $server_url. '/payment/response/exito';
                    $external_reference = $order_info;

                }
            } else {
                // There was some error with the connection so check the message
                $message = $response->status()->message();
                $error = true;
            }

            // Persistimos la respuesta a la transacción
            $additional_info = $response->payment[0]->status()->message()
                     . ' - ' . $response->payment[0]->status()->reason()
                     . ' - ' . $response->payment[0]->reference()
                     . ' - ' . $response->payment[0]->internalReference();

            $payment_response = new PaymentResponse();
            $entityManager = $this->getDoctrine()->getManager();
            $payment_response->setStatusInfo($response->status()->message());
            $payment_response->setStatusCode($response->status()->status());
            $payment_response->setPaymentAttemptId($payment_attempt_id);
            $payment_response->setAdditionalInfo($additional_info);
            $payment_response->setOperationCode($response->payment[0]->status()->reason());
            $entityManager->persist($payment_response);
            $entityManager->flush();

        } catch (Exception $e) {
            $error = true;
            $message = $e->getMessage();
            $error = true;
        }

        return $this->render('payment_response/index.html.twig', [
            'message' => $message,
            'status' => $status,
            'error' => $error,
            'product' => ($product)? $product: '',
            'customer' => ($customer)? $customer : '',
            'order_info' => $order_info
        ]);

    }
}
