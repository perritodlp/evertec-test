<?php


namespace App\Controller;


use App\Entity\Products;
use App\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class OrderController extends AbstractController
{
    /**
     * @Route("/", name="order")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        // Para el inicio de la orden, escogemos al azar, un producto de los cinco creados
        $product_id = rand(1,5);

        // Cada orden, manejará un identificador de cliente creado al azar
        $customer_id = rand(1000,9999);

        // Sacamos datos del producto escogido
        $product = $this->getDoctrine()
            ->getRepository(Products::class)
            ->find($product_id);

        $order = new Orders();

        // Creamos el formulario de la orden
        $form = $this->createFormBuilder($order)
            ->setMethod('POST')
            ->add('customer_name', TextType::class, array(
                'label' => 'Nombre completo: ',
                'required' => true,
                'attr' => array(
                    'class' => 'text form-control validate',
                    'data-validate' => 'isSimpleName'
                )
            ))
            ->add('customer_email', EmailType::class, array(
                'label' => 'Correo electrónico: ',
                'required' => true,
                'attr' => array(
                    'class' => 'text form-control validate',
                    'data-validate' => 'isEmail'
                )
            ))
            ->add('customer_mobile', NumberType::class, array(
                'label' => 'Número celular: ',
                'required' => true,
                'attr' => array(
                    'class' => 'is_required validate form-control',
                    'data-validate' => 'isNumber',
                    'min' => 7,
                    'max' => 10,
                    'size' => 10
                )
            ))
            ->add('product_id', HiddenType::class, array(
                'data' => $product->getId()
            ))
            ->add('net', HiddenType::class, array(
                'data' => $product->getProductValue() // El neto y el total son iguales al valor del producto. No hay descuentos ni bonificaciones
            ))
            ->add('tax', HiddenType::class, array(
                'data' => $product->getProductValue()*0.19 // Se asume el iva fijo del 19%
            ))
            ->add('amount', HiddenType::class, array(
                'data' => 1 // Se asume para la prueba, un solo producto
            ))
            ->add('total', HiddenType::class, array(
                'data' => $product->getProductValue() // El neto y el total son iguales al valor del producto. No hay descuentos ni bonificaciones
            ))
            ->add('customer_id', HiddenType::class, array(
                'data' => $customer_id
            ))
            ->add('payment_method_id', HiddenType::class, array(
                'data' => 15 // Se asume que siempre va el mismo tipo de pago
            ))
            ->add('submitButton', SubmitType::class, array(
                'label' => 'Enviar',
                'attr' => array(
                    'class' => 'btn btn-main btn-lg mt-2'
                )
            ))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $order = $form->getData();

            $sel_product_id = $form->get('product_id')->getData();
            $customer_id = $form->get('customer_id')->getData();
            $payment_method_id = $form->get('payment_method_id')->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            $order_id = $order->getId();

            return $this->redirectToRoute('order_preview', array(
                'order_info' => $sel_product_id . '-' . $customer_id . '-' . $order_id . '-' . $payment_method_id
            ));
        }

        return $this->render('order/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

}