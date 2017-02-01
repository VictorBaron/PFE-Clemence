<?php
// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('prenom')
        ->add('nom')
        ->add('dateDeNaissance', BirthdayType::class)
        ->add('adresse')
        ->add('codePostal')
        ->add('ville')
        ->add('image_profil', FileType::class, array('label' => 'Image de profil'))
        ->add('signature', FileType::class, array('label' => 'Signature'));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

     public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}