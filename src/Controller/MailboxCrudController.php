<?php

namespace App\Controller;

use App\Entity\Mailbox;
use App\Filter\ConcatFilter;
use App\Security\DovecotPasswordEncoder;
use App\Type\AppPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class MailboxCrudController extends AbstractCrudController {
    private PasswordHasherInterface $passwordHasher;

    public function __construct(DovecotPasswordEncoder $passwordEncoder) {
        $this->passwordHasher = $passwordEncoder;
    }

    public static function getEntityFqcn(): string {
        return Mailbox::class;
    }

    public function createEntity(string $entityFqcn) {
        $mailbox = new Mailbox();
        $mailbox->setEnabled(true);
        $mailbox->setQuota(0);
        return $mailbox;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInPlural('Mailboxes')
            ->setEntityLabelInSingular('Mailbox')
        ;
    }
    public function configureFields(string $pageName): iterable {
        return [
            FormField::addPanel('Mailbox details')->setIcon('fa fa-info-circle'),
            Field::new('address', 'Email address')
                ->onlyOnDetail()
                ->onlyOnIndex(),
            Field::new('local_username', 'Username')
                ->setRequired(true)
                ->onlyOnForms()
                ->setColumns(6),
            AssociationField::new('domain')
                ->setRequired(true)
                ->onlyOnForms()
                ->setColumns(6),

            IntegerField::new('quota')
                ->setRequired(true)
                ->setLabel("Quota (MB)")
                ->setHelp('0 is unlimited')
                ->formatValue(fn ($val) => $val == 0 ? 'Unlimited' : $val." MB")
                ->setColumns(2),
            Field::new('enabled')
                ->setColumns('col-md-2 offset-md-4'),
            Field::new('admin')
                ->setColumns(2),
            Field::new('sendonly')
                ->setLabel("Send only?")
                ->setHelp("Don't create a local mailbox for receiving emails<br>".
                    "Useful as a no-reply mailbox")
                ->setColumns(2),

            FormField::addPanel('Passwords')->setIcon('fa fa-key'),
            Field::new('plainPassword', 'Main password')->onlyWhenCreating()
                ->setFormType(PasswordType::class)
                ->setRequired(true),
            Field::new('plainPassword', 'Reset main password')->onlyWhenUpdating()
                ->setFormType(RepeatedType::class)
                ->setRequired(false)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Reset password',
                        'help' => 'Leave blank to not change password',
                        'row_attr' => ['class' => 'col-md-6 col-xl-4'],
                    ],
                    'second_options' => [
                        'label' => 'Repeat new password',
                        'row_attr' => ['class' => 'col-md-6 col-xl-4'],
                    ],
                ]),
            CollectionField::new('appPasswords')
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true)
                ->setEntryType(AppPasswordType::class)
                ->onlyWhenUpdating(),
        ];
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add(ConcatFilter::new(['local_username', '@', 'domain:domain'], 'Email address'))
            ->add('admin')
            ->add('enabled')
        ;
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        $this->addEncodePasswordEventListener($formBuilder);
        return $formBuilder;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        $this->addEncodePasswordEventListener($formBuilder);
        return $formBuilder;
    }

    protected function addEncodePasswordEventListener(FormBuilderInterface $formBuilder) {
        $formBuilder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var Mailbox $mailbox */
            $mailbox = $event->getData();
            if (!is_null($mailbox->getPlainPassword()) && !empty($mailbox->getPlainPassword())) {
                try {
                    $mailbox->setPassword($this->passwordHasher->hash(
                        $mailbox->getPlainPassword(),
                    ));
                } catch (BadCredentialsException $e) {
                    $event->getForm()->get('plainPassword')->addError(new FormError(
                        'Invalid password',
                        null,
                        [],
                        null,
                        $e,
                    ));
                    $event->stopPropagation();
                }
                $mailbox->eraseCredentials();
            }
        });
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void {
        parent::updateEntity($entityManager, $entityInstance);
        $entityManager->refresh($entityInstance);
    }
}
