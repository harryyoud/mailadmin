<?php

namespace App\Type;

use App\Entity\Password;
use App\Security\DovecotPasswordEncoder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class AppPasswordType extends AbstractType {

    public function __construct(
        private DovecotPasswordEncoder $passwordHasher,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('appName', TextType::class, [
                'help' => 'This will form part of the username (e.g. user@domain.com_appname) for this app-specific credential'
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Password',
                'help' => 'Leave blank to leave password unchanged',
                'required' => true,
            ])
        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /* @var Password $appPassword */
            $appPassword = $event->getData();

            $formError = new FormError(
                'Invalid password',
                null,
                [],
                null,
            );

            if (is_null($appPassword->getPlainPassword()) || empty($appPassword->getPlainPassword())) {
                // Password empty, so don't set it
                return;
            }

            try {
                $appPassword->setPassword($this->passwordHasher->hash(
                    $appPassword->getPlainPassword(),
                ));
            } catch (BadCredentialsException $e) {
                $event->getForm()->get('plainPassword')->addError($formError);
                $event->stopPropagation();
            }

            $appPassword->eraseCredentials();
        });
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Password::class,
        ]);
    }
}