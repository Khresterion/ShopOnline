<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => " ",
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Vous devez avoir au moins {{ limit }} caractères de long.',
                        'maxMessage' => 'Vous devez au maximum avoir {{ limit }} caractères de long.'

                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Ce champ ne peut pas contenir de chiffre',
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Prénom'
                ]
            ])
            ->add('lastname', Texttype::class, [
                'label' => " ",
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Vous devez avoir au moins {{ limit }} caractères de long.',
                        'maxMessage' => 'Vous devez au maximum avoir {{ limit }} caractères de long.'

                    ]),
                    new Regex([
                        'pattern' => '/\d/',
                        'match' => false,
                        'message' => 'Ce champ ne peut pas contenir de chiffre',
                    ])
                ],
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => " ",
                'constraints' => [
                    new NotBlank(),

                ],
                'attr' => [
                    'placeholder' => 'E-mail'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe doit être identique.',
                'label' => " ",
                'required' => true,
                'constraints' => [
                    new NotBlank(),

                ],
                'first_options' => [
                    'label' => ' ',
                    'attr' => [
                        'placeholder' => 'Mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => ' ',
                    'constraints' => new NotBlank(),
                    'attr' => [
                        'placeholder' => 'Confirmez votre Mot de Passe.'
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => [
                    'class' => 'btn btn-block btn-info mt-5'
                ]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
