<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Project;
use App\Entity\TypeInter;
use App\Form\RechercheType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheController extends AbstractController
{
    /**
     * @Route("/recherche", name="recherche")
     */
    public function index(Request $request, TaskRepository $taskRepository): Response
    {
        $task = new Task;

        dump($request);
        //'multiple' => true pour le champs user

        $form = $this->createForm(RechercheType::class)
        ->add('user', EntityType::class, [
            'query_builder' => function (UserRepository $er) {
                return $er->createQueryBuilder('u')
                    ->andWhere('u.actif != :val5')
                    ->setParameter('val5', 'non' )
                    ->orderBy('u.mail', 'ASC');
            },
            'class' => User::class,
            'required' => false,
            'choice_label' => 'mail',
            'placeholder' => ' - - Fais ton choix - -',
        ])
        ->add('client', EntityType::class, [
            'query_builder' => function (ClientRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
            },
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
        ])
        ->add('dateD', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required'   => false,
            'empty_data' => null,
            'data' => new \DateTime("- 30 days")
        ])
        ->add('dateF', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'required'   => false,
            'empty_data' => null,
            'data' => new \DateTime("now")
        ])
        ;


        $form->handleRequest($request);

        //var_dump($form->get('date')->getData());

        //&& $form->isValid()

        if ($form->isSubmitted() ) {

           
            $msg = '';

            if ( $form->get('refMantis')->getData() != null ) $msg .= ' / refMantis ' . $form->get('refMantis')->getData();
            if ( $form->get('client')->getData() != null ) $msg .= ' / client ' . $form->get('client')->getData()->getName();
            if ( $form->get('project')->getData() != null ) $msg .= ' / projet ' . $form->get('project')->getData()->getName();
            if ( $form->get('typeInter')->getData() != null ) $msg .= ' / typeInter ' . $form->get('typeInter')->getData()->getName();
            if ( $form->get('user')->getData() != null ) $msg .= ' / user ' . $form->get('user')->getData()->getName();
            
            
            if ( $form->get('dateD')->getData() != null ) $msg .= ' / dateD ' . $form->get('dateD')->getData()->format('d/m/Y');
            if ( $form->get('dateF')->getData() != null ) $msg .= ' / dateF ' . $form->get('dateF')->getData()->format('d/m/Y');
            
            if ($form->get('refMantis')->getData() == null && $form->get('client')->getData() == null && $form->get('project')->getData() == null && $form->get('typeInter')->getData() == null && $form->get('user')->getData() == null && $form->get('dateD')->getData() == null && $form->get('dateF')->getData() == null ) {
                return $this->redirectToRoute('recherche');
            }
            else 
            {
                return $this->render('recherche/index.html.twig', [
                    'form' => $form->createView(),
                    'msg' => $msg,
                    'tasks' => $taskRepository->findByMultiplFields(
                        $form->get('refMantis')->getData(),
                        $form->get('client')->getData(),
                        $form->get('project')->getData(),
                        $form->get('typeInter')->getData(),
                        $form->get('user')->getData(),
                        $form->get('dateD')->getData(),
                        $form->get('dateF')->getData()
                    )
                ]);
            }

        }
        else
        {
            $query = $taskRepository->createQueryBuilder('t')
            ->where('1=1')
            ->orderBy('t.date', 'DESC')
            ->andWhere('t.date >= :val5')
            ->setParameter('val5', $form->get('dateD')->getData())
            ->andWhere('t.date <= :val6')
            ->setParameter('val6', $form->get('dateF')->getData())
            ->getQuery();
            //->setMaxResults(1000);

            $tasks = $query->getResult();


            return $this->render('recherche/index.html.twig', [
                'form' => $form->createView(),
                'tasks' => $tasks,
                'msg' => 'Entrez votre recherche'
            ]);
        }
        
    }
}
