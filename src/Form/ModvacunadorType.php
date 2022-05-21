<?php

namespace App\Form;

use App\Entity\Usuarios;
use App\Entity\Vacunatorios;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModvacunadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', type: HiddenType::class, options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('mail', type: EmailType::class, options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('pass', options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('nombre', options: [
                'label' => 'Nombre y Apellido',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('dni', options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('telefono', options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            // ->add('fecha_baja')
            ->add('vacunatorio_id', EntityType::class, [
                'class' => Vacunatorios::class,
                'choice_value' => 'id',
                'choice_label' => 'nombre',
                'label' => 'Vacunatorio',
                'attr' => [
                    'class' => 'form-control'
                    ]
            ])
            ->add('registrar', type: SubmitType::class, options: [
                'label' => 'Guardar',
                'attr' => [
                    'class' => 'btn btn-primary'
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuarios::class,
        ]);
    }
}
