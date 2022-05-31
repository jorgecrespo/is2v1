<?php

namespace App\Form;

use App\Entity\Vacunatorios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModVacunatorioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', type: HiddenType::class)
            ->add('nombre', options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('direccion', options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('registrar', type: SubmitType::class, options: [
                'label' => 'Guardar',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->add('cancelar', type: SubmitType::class, options: [
                'label' => 'Cancelar',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'onclick' => 'ir_a("/homeadmin")'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vacunatorios::class,
        ]);
    }
}
