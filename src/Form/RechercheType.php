<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Project;
use App\Entity\TypeInter;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('refMantis')
            ->add('user', EntityType::class, [
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.actif != :val5')
                        ->setParameter('val5', 'non' )
                        ->orderBy('u.mail', 'ASC');
                },
                'class' => User::class,
                'required' => false,
                'choice_label' => 'mail',
                'placeholder' => ' - - Fais ton choix - -',
            ])
            ->add('client', EntityType::class, [
                'query_builder' => function (ClientRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'class' => Client::class,
                'required'   => false,
                'choice_label' => 'name',
                'placeholder' => ' - - Fais ton choix - -',
            ])
            ->add('typeInter', EntityType::class, [
                'class' => TypeInter::class,
                'required'   => false,
                'choice_label' => 'name',
                'placeholder' => ' - - Fais ton choix - -',
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'required'   => false,
                'choice_label' => 'name',
                'placeholder' => ' - - Fais ton choix - -',
            ])
            ->add('dateD', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required'   => false,
                'empty_data' => null,
                'data' => new \DateTime("- 30 days")
            ])
            ->add('dateF', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required'   => false,
                'empty_data' => null,
                'data' => new \DateTime("now")
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
