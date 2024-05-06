<?php

namespace App\Form;

use App\Entity\Enseignant;
use App\Entity\Projet;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('duree')
            ->add('pre_requis')
            ->add('contenu')
            ->add('selected')
            ->add('enseignant', EntityType::class, [
                'class' => Enseignant::class,
                'choice_label' => 'id', // Ou tout autre champ de l'entité User à afficher
                'required' => false,
                'placeholder' => 'Sélectionner un enseignant'
            ])
            ->add('etudiants', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id', // Ou tout autre champ de l'entité User à afficher
                'multiple' => true,
                'required' => false,
                'placeholder' => 'Sélectionner des étudiants',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_ET"%');
                },
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
