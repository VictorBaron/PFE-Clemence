<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NousController extends Controller
{
    /**
     * @Route("/Nous")
     */
    public function showAction()
    {
        return $this->render('Nous/show.html.twig', [
            'name' => 'Qui sommes-nous ?'
        ]);
    }
}
