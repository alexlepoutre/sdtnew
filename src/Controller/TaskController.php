<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\TypeInter;
use App\Entity\Project;
use App\Form\Task1Type;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

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
        return $this->render('task/index.html.twig', [
            'msg' => 'Toutes les taches',
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    /**
     * @Route("/rech/{id}", name="task_rech", methods={"GET"})
     */
    public function rech(TaskRepository $taskRepository, $id): Response
    {
        
        //ref_mantis = 10220202
        return $this->render('task/index.html.twig', [
            'msg' => 'Toutes les taches ref mantis ' .$id,
            'tasks' => $taskRepository->findBy(
                ['refMantis' => $id]
            )
        ]);
    }

    /**
     * @Route("/new", name="task_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(Task1Type::class, $task)
        ->add('date', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'data' => new \DateTime("now"),
        ])
        ->add('createdAt', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'data' => new \DateTime("now"),
        ])
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
        ])
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
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
        $form = $this->createForm(Task1Type::class, $task)
        ->add('user', EntityType::class, [
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
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('task_index');
    }
}
