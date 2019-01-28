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
            'required'   => false,
            'placeholder' => ' - - Fais ton choix - -',
        ])
        ->add('client', EntityType::class, [
            'class' => Client::class,
            'required'   => false,
            'choice_label' => 'name',
            'placeholder' => ' - - Fais ton choix - -',
        ])
        ->add('typeInter', EntityType::class, [
            'class' => TypeInter::class,
            'required'   => false,
            'choice_label' => 'name',
            'placeholder' => ' - - Fais ton choix - -',
        ])
        ->add('project', EntityType::class, [
            'class' => Project::class,
            'required'   => false,
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
            else 
            {
                /*
                $entityManager = $this->getEntityManager();
                $query = $entityManager->createQuery(
                    'SELECT p
                    FROM \App\Entity\Task p
                    WHERE p.ref_mantis = :ref
                    ORDER BY p.ref_mantis ASC'
                )->setParameter('ref', $form->get('refMantis')->getData() );
                
                $tasks = $query->getResult();
*/
                $msg = 'Resultat pour ref mantis ' . $form->get('refMantis')->getData();
                if ( $form->get('client')->getData() != null ) $msg .= ' client ' . $form->get('client')->getData()->getName();
                
                return $this->render('recherche/index.html.twig', [
                    'form' => $form->createView(),
                    'msg' => $msg,
                    'tasks' => $taskRepository->findByMultiplFields(
                        $form->get('refMantis')->getData(),
                        $form->get('client')->getData()
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
