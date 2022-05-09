<?php

namespace App\Form;

use App\Entity\Pacientes;
use Symfony\Component\Form\AbstractType;
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
            ->add('mail', type: EmailType::class)
            ->add('pass', type: PasswordType::class)
            // ->add('token')
            ->add('nombre')
            ->add('apellido')
            ->add('de_riesgo')
            ->add('fecha_nac')
            ->add('vacuna_gripe_fecha')
            ->add('vacuna_covid1_fecha')
            ->add('vacuna_covid2_fecha')
            ->add('vacuna_hepatitis_fecha')
            // ->add('notificacion_pendiente')
            ->add('registrar', type: SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pacientes::class,
        ]);
    }
}
