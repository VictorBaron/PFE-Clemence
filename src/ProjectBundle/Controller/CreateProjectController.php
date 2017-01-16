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
    $user = $this->getUser();
    /*if (!is_object($user) || !$user instanceof UserInterface) {
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
      ->add( 'content', FroalaEditorType::class) 
    ;

    // À partir du formBuilder, on génère le formulaire
    $form = $formBuilder->getForm();

        // On récupère l'EntityManager
    $em = $this->getDoctrine()->getManager();

    // Étape 1 : On « persiste » l'entité
    $em->persist($project);

    // Étape 2 : On « flush » tout ce qui a été persisté avant
    $em->flush();

    // Reste de la méthode qu'on avait déjà écrit
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Projet bien enregistré.');

      // Puis on redirige vers la page de visualisation de cettte annonce
      return $this->redirectToRoute('view_project', array('id' => $project->getId()));
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
}
