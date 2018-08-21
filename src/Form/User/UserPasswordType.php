<?php

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 

        $builder
        ->add(
            'password', RepeatedType::class, [
                'type'  =>  PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),  
                'attr'  => [
                    'class' => 'form-input',
                ]      
            ]
        )
        ->add(
            'submit', SubmitType::class, [
                'attr'  =>  [
                    'class'     => 'btn',
                    'label'     =>  'Save :',
                ]         
            ]
        );
    }

}