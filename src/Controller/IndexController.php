<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class IndexController extends AbstractController
{
    #[Route('/', 'app_index')]
    public function __invoke(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('amount', MoneyType::class, [
                'required' => true,
                'currency' => 'CHF',
                'scale' => 0,
                'divisor' => 100,
                'constraints' => [
                    new GreaterThanOrEqual([
                        // I want a value above CHF 5 but need to multiply by the MoneyType his divisor value.
                        // But that leaves me with an error message where "{ compared_value }" contains 500 instead of 5.
                        'value' => 500,
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'successfully submitted!');
        }

        return $this->render('index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}