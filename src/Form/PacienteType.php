<?php

namespace App\Form;

use App\Entity\Pacientes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PacienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mail', type: EmailType::class, options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('pass', type: PasswordType::class, options: [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Contraseña',

            ])
            // ->add('token')
            ->add('nombre', options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('apellido', options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('de_riesgo', options: [
                'attr' => [
                    'class' => 'form-check',
                ],
            ])
            ->add('fecha_nac', type: DateType::class, 
            options: [
                'years' => range(1900,2022),
                'label' => 'Fecha de nacimiento',
                'attr' => ['class' => 'form-group'],
                'placeholder' => [
                    'day' => '--',
                    'month' => '--',
                    'year' => '--'
                ],
                ]
            )
            ->add('terminar_registro_3333', type: SubmitType::class, options: [
                'label' => 'Finalizar Registro',
                'attr' => [
                    'id'=>'finregistro',
                    'class' => 'btn btn-primary'
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pacientes::class,
        ]);
    }
}
