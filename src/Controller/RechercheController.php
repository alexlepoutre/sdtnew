<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\TypeInter;
use App\Entity\Project;
use App\Entity\Task;
use App\Form\RechercheType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TaskRepository;

class RechercheController extends AbstractController
{
    /**
     * @Route("/recherche", name="recherche")
     */
    public function index(Request $request, TaskRepository $taskRepository): Response
    {
        $task = new Task;

        $form = $this->createForm(RechercheType::class)
        ->add('user', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'mail',
            'placeholder' => ' - - Fais ton choix - -',
        ])
        ->add('client', EntityType::class, [
            'class' => Client::class,
            'choice_label' => 'name',
            'placeholder' => ' - - Fais ton choix - -',
        ])
        ->add('typeInter', EntityType::class, [
            'class' => TypeInter::class,
            'choice_label' => 'name',
            'placeholder' => ' - - Fais ton choix - -',
        ])
        ->add('project', EntityType::class, [
            'class' => Project::class,
            'choice_label' => 'name',
            'placeholder' => ' - - Fais ton choix - -',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ( $form->get('refMantis')->getData()  == '' ) {
                return $this->render('recherche/index.html.twig', [
                    'form' => $form->createView(),
                    'msg' => 'Coucou',
                ]);
            }    
            else {
                return $this->render('recherche/index.html.twig', [
                    'form' => $form->createView(),
                    'msg' => 'Resultat pour ref mantis' . $form->get('refMantis')->getData(),
                    'tasks' => $taskRepository->findBy(
                        [
                            'refMantis' => $form->get('refMantis')->getData(),
                            'client' => $form->get('client')->getData(),
                         ]
                    )
                ]);
                    }

    

        }
        else
        {
            return $this->render('recherche/index.html.twig', [
                'form' => $form->createView(),
                'tasks' => $taskRepository->findAll(),
                'msg' => 'Entrez votre recherche',
                'controller_name' => 'RechercheController',
            ]);
        }
        
    }
}
