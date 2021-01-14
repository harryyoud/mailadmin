<?php

namespace App\Controller;

use App\Entity\Alias;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class AliasCrudController extends AbstractCrudController {
    public static function getEntityFqcn(): string {
        return Alias::class;
    }

    public function configureFields(string $pageName): iterable {
        return [
            Field::new('enabled'),

            FormField::addPanel('From'),
            Field::new('source_username', 'Username')
                ->setRequired(true),
            AssociationField::new('source_domain', 'Domain')
                ->setRequired(true),

            FormField::addPanel('To'),
            Field::new('destination_username', 'Username')
                ->setRequired(true),
            Field::new('destination_domain', 'Domain')
                ->setRequired(true),
        ];
    }
}
