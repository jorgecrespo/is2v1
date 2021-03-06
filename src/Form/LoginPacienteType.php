<?php

namespace App\Form;

use App\Entity\Pacientes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginPacienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('mail', type: EmailType::class)->setAttribute('name', '_username')
            ->add('mail', type: EmailType::class, options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('pass', type: PasswordType::class, options: [
                'label' => 'Contraseña',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('token', options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('login', type: SubmitType::class,  options: [
                'label' => 'Iniciar Sesión',
                'attr' => [
                    'class' => 'btn btn-primary'
                    ]
                ])
            // ->setAction('/')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pacientes::class,
        ]);
    }
}
