<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CursoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titulo', TextType::class, 
                array("required" => "required", "attr" => array(
                    "class" => "form-tutulo"
                )))
                ->add('descripcion', TextareaType::class)
                //->add('precio', TextType::class)
                //->add('precio', ChoiceType::class, array(
                //    "choices" => array(
                //        "hombre" => "Hombre",
                //        "mujer" => "mujer"
                //    )
                //))
                ->add('precio', \Symfony\Component\Form\Extension\Core\Type\CheckboxType::class, array(
                    "label" => "Mostrar precio ?",
                    "required" => true
                ))
                ->add('Guardar', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Curso'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_curso';
    }


}
