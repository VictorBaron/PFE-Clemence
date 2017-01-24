<?php

namespace ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ProjectBundle\Entity\Project;
use ProjectBundle\Form\ProjectType;
use FOS\UserBundle;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use KMS\FroalaEditorBundle\Twig\FroalaExtension;

class CreateProjectController extends Controller
{
    
    public function create_projectAction(Request $request)
    {
        // On crée un objet Advert, et on l'initialise.
    $project = new Project();
    $id = $project->getId();
    $user = $this->getUser();
    $userID = $user->getId();
   /* if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }*/
    $project->setAuthor($user);
    $project->setAuthorID($userID);
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

    return $this->render('ProjectBundle:Project:view_project.html.twig', array(
      'project' => $project,
    ));
  }

  public function my_projectsAction(Request $request){
    $em=$this->getDoctrine()->getManager();
    $user=$this->getUser();
    $userID = $user->getId();

    $listProjects = $em->getRepository('ProjectBundle:Project')->findByAuthorID($userID);

    return $this->render('ProjectBundle:Project:my_projects.html.twig', array(
      'listProjects' => $listProjects,
    ));
  }

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
      return $this->redirectToRoute('view_project', array('id' => $project->getId()));
    }
    return $this->render('ProjectBundle:Project:create_project.html.twig', array(
      'project' => $project,
      'form' => $form->createView(),
    ));
  }
}