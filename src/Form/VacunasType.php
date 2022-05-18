<?php

namespace App\Form;

use App\Entity\Pacientes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacunasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vacuna_gripe_fecha', type: DateType::class, options: [
                'label' => 'Fecha de Vacunación contra la gripe',
                'attr' => ['id'=>'vacuna_gripe_fecha'],
                'placeholder' => '--',
                'required' => false,
                'years' => range(1900,2022),

                ])
            ->add('vacuna_covid1_fecha', type: DateType::class, options: [
                'label' => 'Fecha de vacunación contra el Covid19 Dosis 1',
                'attr' => ['id'=>'vacuna_covid1_fecha'],
                'placeholder' => '--',
                'required' => false,
                'years' => range(1900,2022),
                
                ])
            ->add('vacuna_covid2_fecha', type: DateType::class, options: [
                'label' => 'Fecha de vacunación contra el Covid19 Dosis 2',
                'attr' => ['id'=>'vacuna_covid2_fecha'],
                'placeholder' => '--',
                'required' => false,
                'years' => range(1900,2022),

                ])
            ->add('vacuna_hepatitis_fecha', type: DateType::class, options: [
                'label' => 'Fecha de vacunación contra la hepatitis',
                'attr' => ['id'=>'vacuna_hepatitis_fecha'],
                'placeholder' => '--',
                'required' => false,
                'years' => range(1900,2022),

                ])
            ->add('registrar', type: SubmitType::class, options: [
                'label' => 'Registrar Vacunas',
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
