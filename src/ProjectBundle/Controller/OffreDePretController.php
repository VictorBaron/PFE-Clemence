<?php

namespace ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ProjectBundle\Entity\Project;
use ProjectBundle\Entity\OffreDePret;
use ProjectBundle\Form\ProjectType;
use FOS\UserBundle;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use KMS\FroalaEditorBundle\Twig\FroalaExtension;

class OffreDePretController extends Controller
{
	public function offre_de_pretAction(Request $request, $id) {
		$offreDePret = new OffreDePret;
		$user=$this->getUser();
		$offreDePret->setLender($user->getId());

    	// On crée le FormBuilder grâce au service form factory
    	$form  = $this->get('form.factory')->create(OffreDePretType::class, $offreDePret);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() ) {

			
      
	        // On enregistre notre objet $project dans la base de données
	        $em = $this->getDoctrine()->getManager();
	        $em->persist($offreDePret);
	        $em->flush();

	        $request->getSession()->getFlashBag()->add('notice', 'Projet financé !');
	        
	        // On redirige vers la page de visualisation de l'annonce 
	        return $this->redirectToRoute('view_project', array('id' => $id));
	      
	    }
	}

}