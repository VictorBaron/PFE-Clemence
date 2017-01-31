<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 13/01/2017
 * Time: 17:10
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends Controller
{
    /**
     * @Route=("/")
     */
    public function showAction()
    {
        return $this->render('Accueil/show.html.twig', [
            'name' => 'Accueil'
        ]);
    }
}