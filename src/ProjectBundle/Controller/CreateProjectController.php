<?php

namespace ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ProjectBundle\Entity\Project;
use ProjectBundle\Entity\OffreDePret;
use ProjectBundle\Form\ProjectType;
use FOS\UserBundle;

class CreateProjectController extends Controller
{
    
    public function create_projectAction(Request $request)
    {
        // On crée un objet Advert, et on l'initialise.
    $project = new Project();
    $author = $this->getUser();
    /*if($author->getPrenom()==null||$author->getNom()==null||$author->DateDeNaissance()==null){
      return $this->redirectToRoute('fos_show_profile_show');
    }*/

    $project->setAuthor($author);
    $project->setContent("Votre projet ici.");
    $project->setTitle('Titre du projet');
    // On crée le FormBuilder grâce au service form factory
    $form  = $this->get('form.factory')->create(ProjectType::class, $project);


    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() ) {
      
        // On enregistre notre objet $project dans la base de données
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', '¨Projet bien enregistré.');
        
        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('view_project', array('id' => $project->getId()));
      
    }

    // On passe la méthode createView() du formulaire à la vue afin qu'elle puisse afficher le formulaire
    return $this->render('ProjectBundle:Project:create_project.html.twig', array(
      'project' => $project,
      'form' => $form->createView(),
      'name' => "Créer un projet",
    ));

    }

  public function deleteAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();

    $project = $em->getRepository('ProjectBundle:Project')->find($id);

    if (null === $project) {
      throw new NotFoundHttpException("Le projet d'id ".$id." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em->remove($project);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "Le projet a bien été supprimé.");

      return $this->redirectToRoute('project_homepage');
    }
    
    return $this->render('ProjectBundle:Project:delete.html.twig', array(
      'project' => $project,
      'form'   => $form->createView(),
    ));
  }

  
  public function view_projectAction($id)
  {
    $em=$this->getDoctrine()->getManager();
    $project=$em->getRepository('ProjectBundle:Project')->find($id);

    //Savoir si c'est le propriétaire qui regarde son annonce ou pas
    $user=$this->getUser();
    $proprietaire=false;
    if($user == $project->getAuthor()) {
      $proprietaire=true;
    }
    //récupérer toutes les propositions
    $offres=$em->getRepository('ProjectBundle:OffreDePret')->findByProject($project);


    return $this->render('ProjectBundle:Project:view_project.html.twig', array(
      'project' => $project,
      'proprietaire' => $proprietaire,
      'offres' => $offres,
      'name' => 'Projet',
    ));
  }

  public function my_projectsAction(Request $request){
    $em=$this->getDoctrine()->getManager();
    $user=$this->getUser();

    $listProjects = $em->getRepository('ProjectBundle:Project')->findByAuthor($user);
    foreach($listProjects as $project)
    {
        $offres = $em->getRepository('ProjectBundle:OffreDePret')->findBy(array('project' => $project));
    }
    if (empty($listProjects))
      $offres=null;

    return $this->render('ProjectBundle:Project:my_projects.html.twig', array(
      'listProjects' => $listProjects,
      'offres' => $offres,
    ));
  }


  /**
     * @Route("/edit/{id}", name="edit_project")
     */
  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $project = $em->getRepository('ProjectBundle:Project')->find($id);
    if (null === $project) {
      throw new NotFoundHttpException("Le projet d'id ".$id." n'existe pas.");
    }
    $form  = $this->get('form.factory')->create(ProjectType::class, $project);
    // Ici encore, il faudra mettre la gestion du formulaire
    if ($request->isMethod('POST')  && $form->handleRequest($request)->isValid())  {
      $em->flush();
      $request->getSession()->getFlashBag()->add('notice', 'Projet bien modifié.');

      return $this->redirectToRoute('view_project', array('id' => $id));
    }
    return $this->render('ProjectBundle:Project:create_project.html.twig', array(
      'project' => $project,
      'form' => $form->createView(),
    ));
  }
}