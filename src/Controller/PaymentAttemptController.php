<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\PaymentAttempt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Dnetix\Redirection\PlacetoPay;

class PaymentAttemptController extends AbstractController
{
    /**
     * @Route("/payment/attempt/{order_info}", name="payment_attempt")
     * @param $order_info
     * @param Request $request
     * @throws \Exception
     */
    public function index($order_info, Request $request)
    {
        [$product_id, $customer_id, $order_id, $payment_method_id] = explode("-", $order_info);
        $placetopay_api = $this->getParameter('placetopay_api');

        try {
            $payload = $this->buildPayload($order_id, $request, $order_info);
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

            $response = $placetopay->request($payload);

            if ($response->isSuccessful()) {
                $em = $this->getDoctrine()->getManager();
                $payment_attempt = $em->getRepository(PaymentAttempt::class)->findOneBy(['orderId' => $order_id]);;
                $payment_attempt->setRequestId($response->requestId());
                $em->persist($payment_attempt);
                $em->flush();                

                // Redirect the client to the processUrl or display it on the JS extension
                return new RedirectResponse($response->processUrl());
            } else {
                // There was some error so check the message
                return $this->redirectToRoute('payment_response', array(
                    'status' => 'error',
                    'message' => $response->status()->message()
                ));
            }
        } catch (Exception $e) {
            return $this->redirectToRoute('payment_response', array(
                'message' => $e,
                'status' => 'error'
            ));
        }
    }

    public function buildPayload($order_id, $request, $order_info)
    {
        $server_url = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $expiration = date('c', strtotime('+1 day'));

        $order = $this->getDoctrine()
            ->getRepository(Orders::class)
            ->find($order_id);

        $buyer = array(
            'document'          => '94381714',
            'documentType'      => 'CC',
            'name'              => $order->getCustomerName(),
            'surname'           => 'Apellido',
            'email'             => $order->getCustomerEmail(),
            'mobile'            => $order->getCustomerMobile(),
            'address'           => array(
                'street'        => 'Cra 2 24 - 46',
                'city'          => 'Cali',
                'state'         => 'Valle del Cauca',
                'country'       => 'CO',
                'postalCode'    => '760041',
                'phone'         => '3261019'
            )
        );

        $payment = array(
            'reference'         => $order_info,
            'description'       => 'Compra de prueba',
            'amount'            => array(
                'currency'      => 'COP',
                'total'         => number_format($order->getTotal(),'2','.','')
            ),
            'allowPartial'      => false
        );

        return array(
            "locale"        => "es_CO",
            "buyer"         =>  $buyer,
            "payment"       =>  $payment,
            "expiration"    =>  $expiration,
            "returnUrl"     =>  $server_url. "/payment/response/". $order_info,
            "userAgent"     => "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, likeGecko) Chrome/52.0.2743.82 Safari/537.36",
            "ipAddress"     =>  "127.0.0.1"
        );
    }
}
