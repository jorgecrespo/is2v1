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

class VacunadorType extends AbstractType
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
                'label' => 'Contraseña',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            // ->add('es_admin')
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
                'label' => 'Vacunatorio',
                'choice_value' => 'id',
                'choice_label' => 'nombre',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('registrar', type: SubmitType::class, options: [
                'attr' => ['class' => 'btn btn-primary'],
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
