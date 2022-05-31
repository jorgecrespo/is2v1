<?php

namespace App\Form;

use App\Entity\Aplicaciones;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AplicacionVacunaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'id',
                 type: HiddenType::class, 
                options: [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ]
            )
            ->add('efectos', options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('lote', options: [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add(
                'turno_id',
                type: HiddenType::class, 
                options: [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ]
            )
            ->add('guardar', type: SubmitType::class,  options: [
                'label' => 'Guardar',
                'attr' => [
                    'class' => 'btn btn-primary'
                    ]
                ])
                ->add('cancelar', type: SubmitType::class,  options: [
                    'label' => 'Cancelar',
                    'attr' => [
                        'class' => 'btn btn-primary',
                        'onclick' => 'btn_cancelar_reg_aplicacion()'
                        ]
                    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aplicaciones::class,
        ]);
    }
}
