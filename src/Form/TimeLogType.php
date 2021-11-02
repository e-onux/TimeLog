<?php

namespace App\Form;

use App\Entity\TimeLog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TimeLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('work_day')
            ->add('start_time')
            ->add('end_time')
            ->add('project')
        ;
        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($data->getEndTime()) {
                $duration = $data->getEndTime()->getTimestamp() - $data->getStartTime()->getTimestamp();
                $data->setDuration($duration);           
                $event->setData($data);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TimeLog::class,
        ]);
    }
}


