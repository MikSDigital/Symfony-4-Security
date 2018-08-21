<?php

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FirstUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 

        $builder
        ->add(
            'username', TextType::class, [
                'attr'  => [
                    'class' => 'form-input',
                    'label'     =>  'User name :',
                    'required'  =>  true,   
                ]         
            ]
        )
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
            'email', TextType::class, [
                'attr'  =>  [
                    'class' => 'form-input',
                    'label'     =>  'E-mail :',
                    'required'  =>  true,   
                ]         
            ]
        )
        ->add(
            'roles', ChoiceType::class, [
                'multiple'      => true,
                'choices' => [
                    'Admin'     =>  'ROLE_ADMIN'
                ],
                'attr'          => [
                    'class'     => 'form-input',
                    'label'     => 'Roles :',
                    'required'  => true
                ],
                'data'     => ['ROLE_ADMIN']    
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