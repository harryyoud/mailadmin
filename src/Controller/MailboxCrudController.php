<?php

namespace App\Controller;

use App\Entity\Mailbox;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MailboxCrudController extends AbstractCrudController {
    public static function getEntityFqcn(): string {
        return Mailbox::class;
    }

    public function configureFields(string $pageName): iterable {
        return [
            FormField::addPanel('Mailbox details')->setIcon('fa fa-info-circle'),
            Field::new('username')
                ->setRequired(true),
            AssociationField::new('domain')
                ->setRequired(true),
            IntegerField::new('quota')
                ->setRequired(true)
                ->setLabel("Quota (MB)")
                ->setHelp('0 is unlimited')
                ->formatValue(fn ($val) => $val == 0 ? 'Unlimited' : $val." MB"),
            Field::new('enabled'),
            Field::new('sendonly')
                ->setLabel("Send only?")
                ->setHelp("Don't create a local mailbox for receiving emails<br>".
                    "Useful as a no-reply mailbox"),

            FormField::addPanel('Change password')->setIcon('fa fa-key'),
            Field::new('plainPassword', 'New password')->onlyOnForms()
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Reset password',
                        'help' => 'Leave blank to not change password',
                    ],
                    'second_options' => ['label' => 'Repeat password'],
                ])
        ];
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
                $mailbox->setPassword($mailbox->getPlainPassword());
            }
        });
    }

}
