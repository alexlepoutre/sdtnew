<?php

namespace App\Controller;

use App\Entity\TypeInter;
use App\Form\TypeInterType;
use App\Repository\TypeInterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/type/inter")
 */
class TypeInterController extends AbstractController
{
    /**
     * @Route("/", name="type_inter_index", methods={"GET"})
     */
    public function index(TypeInterRepository $typeInterRepository): Response
    {
        return $this->render('type_inter/index.html.twig', [
            'type_inters' => $typeInterRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_inter_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeInter = new TypeInter();
        $form = $this->createForm(TypeInterType::class, $typeInter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeInter);
            $entityManager->flush();

            return $this->redirectToRoute('type_inter_index');
        }

        return $this->render('type_inter/new.html.twig', [
            'type_inter' => $typeInter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_inter_show", methods={"GET"})
     */
    public function show(TypeInter $typeInter): Response
    {
        return $this->render('type_inter/show.html.twig', [
            'type_inter' => $typeInter,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_inter_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeInter $typeInter): Response
    {
        $form = $this->createForm(TypeInterType::class, $typeInter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_inter_index', [
                'id' => $typeInter->getId(),
            ]);
        }

        return $this->render('type_inter/edit.html.twig', [
            'type_inter' => $typeInter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_inter_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TypeInter $typeInter): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeInter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeInter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_inter_index');
    }
}
