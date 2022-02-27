<?php

namespace App\Controller;

use App\Entity\TlsPolicy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TlsPolicyCrudController extends AbstractCrudController {
    public static function getEntityFqcn(): string {
        return TlsPolicy::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInPlural('TLS Policies')
            ->setEntityLabelInSingular('TLS Policy')
            ->setFormOptions([
                'error_mapping' => [
                    'matchedBracketsInDomain' => 'domain',
                ]
            ])
        ;
    }

    public function configureFields(string $pageName): iterable {
        return [
            TextField::new('domain')
                ->setHelp('In Postfix domain format, you may use ports or surround in square brackets to skip MX lookups'),
            ChoiceField::new('policy')
                ->setChoices(array_combine(array_values(TlsPolicy::POLICIES), array_values(TlsPolicy::POLICIES))),
            TextareaField::new('params', 'Extra parameters')
                ->setRequired(false)
                ->setFormTypeOption('empty_data', '')
                ->setHelp('View the <a href="http://www.postfix.org/postconf.5.html#smtp_tls_policy_maps">Postfix documentation</a> for more information')
        ];
    }
}
