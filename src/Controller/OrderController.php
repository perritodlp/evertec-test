<?php


namespace App\Controller;


use App\Entity\Products;
use App\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/")
     */
    public function homepage(Request $request)
    {
        $product_id = rand(1,5);

        $order = new Orders();

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
                'data' => $product_id
            ))
            ->add('customer_id', HiddenType::class, array(
                'data' => 54321
            ))
            ->add('payment_method_id', HiddenType::class, array(
                'data' => 15
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('order_preview', array(
                'product_id' => $sel_product_id,
                'customer_id' => $customer_id
            ));
        }

        return $this->render('order/homepage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/order-preview/{product_id}/{customer_id}", name="order_preview")
     * @param $product_id
     * @param $customer_id
     * @return Response
     */
    public function orderPreview($product_id, $customer_id)
    {
        $product = $this->getDoctrine()
                        ->getRepository(Products::class)
                        ->find($product_id);

        return $this->render('order/order-preview.html.twig', [
            'product' => $product,
            'customer_id' => $customer_id
        ]);
    }
}