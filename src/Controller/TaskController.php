<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Client;
use App\Form\TaskType;
use App\Entity\Project;
use App\Entity\TypeInter;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\ProjectRepository;
use App\Repository\TypeInterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/task")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="task_index", methods={"GET"})
     */
    public function index(TaskRepository $taskRepository): Response
    {

        //        ->orderBy('p.date', 'DESC') 
        
        $query = $taskRepository->createQueryBuilder('p')
        ->where('1=1')
        ->getQuery()
        ->setMaxResults(100);

        $tasks = $query->getResult();

        return $this->render('task/index.html.twig', [
            'msg' => 'Toutes les taches',
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        if ( $this->getUser()->getRoles()[0] == 'ROLE_ADMIN' ) {

            $form->add('user', EntityType::class, [
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.actif != :val5')
                        ->setParameter('val5', 'non' )
                        ->orderBy('u.mail', 'ASC');
                },
                'data' => $this->getUser(),
                'class' => User::class,
                'choice_label' => 'mail',
            ]);
        }

        $form->add('client', EntityType::class, [
            'query_builder' => function (ClientRepository $er) {
                return $er->createQueryBuilder('u')
                    ->andWhere('u.actif != :val5')
                    ->setParameter('val5', 'non' )
                    ->orderBy('u.name', 'ASC');
            },
            'class' => Client::class,
            'choice_label' => 'name',
            'placeholder' => ' - - Fais ton choix - -',
        ])
        ->add('typeInter', EntityType::class, [
            'query_builder' => function (TypeInterRepository $er) {
                return $er->createQueryBuilder('u')
                    ->andWhere('u.actif != :val5')
                    ->setParameter('val5', 'non' )
                    ->orderBy('u.name', 'ASC');
            },
            'class' => TypeInter::class,
            'choice_label' => 'name',
            'placeholder' => ' - - Fais ton choix - -',
        ])
        ->add('project', EntityType::class, [
            'query_builder' => function (ProjectRepository $er) {
                return $er->createQueryBuilder('u')
                    ->andWhere('u.actif != :val5')
                    ->setParameter('val5', 'non' )
                    ->orderBy('u.name', 'ASC');
            },
            'class' => Project::class,
            'choice_label' => 'name',
            'placeholder' => ' - - Fais ton choix - -',
        ])
        ->add('content', TextareaType::class, [
            'attr' => ['class' => 'tinymce'],
        ])
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $task->setCreatedAt(new \DateTime());
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('task_index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_show", methods={"GET"})
     */
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Task $task): Response
    {
        $form = $this->createForm(TaskType::class, $task)
        ->add('user', EntityType::class, [
            'query_builder' => function (UserRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.mail', 'ASC');
            },
            'class' => User::class,
            'choice_label' => 'name',
        ])
        ->add('client', EntityType::class, [
            'class' => Client::class,
            'choice_label' => 'name',
        ])
        ->add('typeInter', EntityType::class, [
            'class' => TypeInter::class,
            'choice_label' => 'name',
        ])
        ->add('project', EntityType::class, [
            'class' => Project::class,
            'choice_label' => 'name',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_index', [
                'id' => $task->getId(),
            ]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="task_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Task $task): Response
    {
        if ( $this->getUser()->getRoles()[0] == 'ROLE_ADMIN' ) {
            if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($task);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('task_index');
    }
}
