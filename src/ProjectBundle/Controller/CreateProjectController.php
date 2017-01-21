<?php

namespace ProjectBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ProjectBundle\Entity\Project;
use FOS\UserBundle;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use KMS\FroalaEditorBundle\Twig\FroalaExtension;

class CreateProjectController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function create_projectAction(Request $request)
    {
        // On crée un objet Advert, et on l'initialise.
    $project = new Project();
    $id = $project->getId();
    $user = $this->getUser();
   /* if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }*/
    $project->setAuthor($user);
    $project->setContent("Votre projet ici.");
    $project->setTitle('Titre du projet');
    // On crée le FormBuilder grâce au service form factory
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $project);

    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $formBuilder
      ->add('title',     TextType::class)
      ->add('content', FroalaEditorType::class)
      ->add('save',      SubmitType::class)
    ;

    // À partir du formBuilder, on génère le formulaire
    $form = $formBuilder->getForm();

        // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Étape 1 : On « persiste » l'entité
    $em->persist($project);

    // Étape 2 : On « flush » tout ce qui a été persisté avant
    $em->flush();
    echo $id;
    if ($request->isMethod('POST')) {
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
      $form->handleRequest($request);

      // On vérifie que les valeurs entrées sont correctes
      // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      if ($form->isValid()) {
        // On enregistre notre objet $advert dans la base de données, par exemple

        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', '¨Projet bien enregistré.');
        
        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('view_project', array('id' => $project->getId()));
      }
    }

    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule
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

  public function save_projectAction(Request $request, $project)
  {
    $em = $this->getDoctrine()->getManager();
    $id = $project->getId();

    $projectInBDD = $em->getRepository('ProjectBundle:Project')->find($id);

    if (null === $projectInBDD) {
      throw new NotFoundHttpException("Le projet d'id ".$id." n'existe pas.");
    }

    $em = $this->getDoctrine()->getManager();
    //$content=$request->getContent();
    $projectInBDD = $project;
    $em->persist($project);
    $em->flush();

  }

  public function view_projectAction($id)
  {



    $em=$this->getDoctrine()->getManager();
    $project=$em->getRepository('ProjectBundle:Project')->find($id);

    return $this->render('ProjectBundle:Project:view_project.html.twig', array(
      'project' => $project,
    ));
  }
}