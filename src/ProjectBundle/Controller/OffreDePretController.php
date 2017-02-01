<?php

namespace ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ProjectBundle\Entity\Project;
use ProjectBundle\Entity\OffreDePret;
use ProjectBundle\Form\ProjectType;
use ProjectBundle\Form\OffreDePretType;
use FOS\UserBundle;
use AppBundle\Entity\User;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use KMS\FroalaEditorBundle\Twig\FroalaExtension;

class OffreDePretController extends Controller
{
	public function create_offre_de_pretAction($id, Request $request) {
		
		$offreDePret = new OffreDePret;
		$user=$this->getUser();
		$offreDePret->setLender($user);

		$em = $this->getDoctrine()->getManager();

		//Récupérer l'auteur du projet
    	$project = $em->getRepository('ProjectBundle:Project')->find($id);
    	$offreDePret->setProject($project);
    	$offreDePret->setNeedToAccept($project->getAuthor());

    	// On crée le FormBuilder grâce au service form factory
    	$form  = $this->get('form.factory')->create(OffreDePretType::class, $offreDePret);

		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() ) {

	        // On enregistre notre objet dans la base de données
	        $em->persist($offreDePret);
	        $em->flush();

	        $request->getSession()->getFlashBag()->add('notice', 'Projet financé !');
	        
	        // On redirige vers la page de visualisation de l'annonce 
	        return $this->redirectToRoute('view_project', array('id' => $id));
	      
	    }

	    return $this->render('ProjectBundle:OffreDePret:offreDePret.html.twig', array(
      		'offre' => $offreDePret,
      		'form'   => $form->createView(),
    		));
	}


	public function edit_offre_de_pretAction(Request $request, $id) {

		$em = $this->getDoctrine()->getManager();
		$offreDePret = $em->getRepository('ProjectBundle:OffreDePret')->find($id);
		if (null === $offreDePret) {
      		throw new NotFoundHttpException("Cette offre n'existe pas.");
    	}
    	

    	$project=$offreDePret->getProject();

    	
		// On crée le FormBuilder grâce au service form factory
    	$form  = $this->get('form.factory')->create(OffreDePretType::class, $offreDePret);


		if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() ) {

			$user=$this->getUser();
	    	if($user==$offreDePret->getLender()){
	    		$offreDePret->setNeedToAccept($offreDePret->getProject()->getAuthor());
	    	}
	    	else {
	    		$offreDePret->setNeedToAccept($offreDePret->getLender());
	    	}
	        // On enregistre notre objet dans la base de données
	        $em->flush();
	    	$this->emailOffrePret($offreDePret);

	        $request->getSession()->getFlashBag()->add('notice', 'Offre modifiée !');
	        
	        // On redirige vers la page pour signer le contrat
	        return $this->redirectToRoute('view_project', array('id' => $project->getId()));
	      
	    }

	     return $this->render('ProjectBundle:OffreDePret:offreDePret.html.twig', array(
      		'offre' => $offreDePret,
      		'form'   => $form->createView(),
    		));

	}

	public function view_offre_de_pretAction(Request $request, $id) {

		$em = $this->getDoctrine()->getManager();
		$offreDePret = $em->getRepository('ProjectBundle:OffreDePret')->find($id);

		return $this->render('ProjectBundle:OffreDePret:viewOffreDePret.html.twig', array(
      		'offre' => $offreDePret,
    		));
	}

	public function accepter_offreAction(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$offreDePret = $em->getRepository('ProjectBundle:OffreDePret')->find($id);
		$project = $offreDePret->getProject();

		//TODO : générer le contrat

		$project->setSommeRecue($project->getSommeRecue()+$offreDePret->getSomme());
		$offreDePret->setNeedToAccept(null);
		$offreDePret->setDatePret(new \Datetime());
		$em->flush();

		return $this->redirectToRoute('pdftest',array('id'=> $id));
		//return $this->redirectToRoute('view_project', array('id' => $project->getId() ));
	}


	public function emailOffrePret (OffreDePret $offre){

		 $message = \Swift_Message::newInstance()
	        ->setSubject('Hello Email')
	        ->setFrom('victor.baron2@gmail.com')
	        ->setTo($offre->getNeedToAccept()->getEmail())
	        ->setBody(
	            $this->renderView(
	                // app/Resources/views/Emails/registration.html.twig
	                'Emails/offreDePret.html.twig',
	                array('offre' => $offre)
	            ),
	            'text/html'
	        );
       
    $this->get('mailer')->send($message);

	}

}