<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/user/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $user->setActif('oui');
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(UserType::class, $user);

        if ( $this->getUser()->getRoles()[0] == 'ROLE_ADMIN' ) {
            $form->add('actif', ChoiceType::class, [
                'choices'  => [
                    'Oui' => 'oui',
                    'Non' => 'non',
                ]
            ])
            ->add('roles', CollectionType::class, [
                'entry_type'   => ChoiceType::class,
                'entry_options'  => [
                    'choices'  => [
                        'ROLE_USER' => 'ROLE_USER',
                        'ROLE_ADMIN'     => 'ROLE_ADMIN',
                    ],
                ],
            ]);
        } 

        $form->add('passwd', PasswordType::class, [
            'required' => false,
            'label' => 'Mot de passe'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            if ( $form->get('passwd')->getData() != '' ) {
                $encoded = $encoder->encodePassword($user, $user->getPasswd());
                $user->setPassword($encoded);    
            }
            
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [
              'id' => $user->getId(),
            ]);
        }

        if ( $user->getMail() == $this->getUser()->getMail() || $this->getUser()->getRoles()[0] == 'ROLE_ADMIN' )
        {
            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);    
        }
        else
        {
            return $this->render('user/show.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        }
        
    }

    /**
     * @Route("/admin/user/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ( $this->getUser()->getRoles()[0] == 'ROLE_ADMIN' ) {
            if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($user);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('user_index');
    }
}
