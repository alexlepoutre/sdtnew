<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SdtController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $user = new User;

        return $this->render('sdt/index.html.twig', [
            'user' => $user
        ]);
    }
}
