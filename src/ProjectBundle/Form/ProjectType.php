<?php

namespace ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use KMS\FroalaEditorBundle\Twig\FroalaExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */


  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('title',     TextType::class)
      ->add('content', FroalaEditorType::class)
      ->add('sommeDemandee',    IntegerType::class)
      ->add('dateFin',     DateType::class)
      ->add('save',      SubmitType::class)
    ;
  }

  public function configureOptions(OptionsResolver $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'ProjectBundle\Entity\Project'
    ));
  }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'projectbundle_project';
    }


}
