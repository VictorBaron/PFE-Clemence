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
        // On crée un objet Advert
    $project = new Project();

    // On crée le FormBuilder grâce au service form factory
    $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $project);

    // On ajoute les champs de l'entité que l'on veut à notre formulaire
    $formBuilder
      ->add('title',     TextType::class)
      ->add( 'content', FroalaEditorType::class) 
    ;

    // À partir du formBuilder, on génère le formulaire
    $form = $formBuilder->getForm();

    // On passe la méthode createView() du formulaire à la vue
    // afin qu'elle puisse afficher le formulaire toute seule
    return $this->render('ProjectBundle:Project:create_project.html.twig', array(
      'form' => $form->createView(),
    ));

    }
}
