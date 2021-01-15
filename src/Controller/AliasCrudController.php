<?php

namespace App\Controller;

use App\Entity\Alias;
use App\Filter\ConcatFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ComparisonFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class AliasCrudController extends AbstractCrudController {
    public static function getEntityFqcn(): string {
        return Alias::class;
    }

    public function configureFields(string $pageName): iterable {
        return [
            Field::new('enabled'),

            FormField::addPanel('From'),
            Field::new('source_address', 'From')
                ->onlyOnIndex(),
            Field::new('source_username', 'Username')
                ->setRequired(true)
                ->onlyOnDetail()
                ->onlyOnForms(),
            AssociationField::new('source_domain', 'Domain')
                ->setRequired(true)
                ->onlyOnDetail()
                ->onlyOnForms(),

            FormField::addPanel('To'),
            Field::new('destination_address', 'To')
                ->onlyOnIndex(),
            Field::new('destination_username', 'Username')
                ->setRequired(true)
                ->onlyOnDetail()
                ->onlyOnForms(),
            Field::new('destination_domain', 'Domain')
                ->setRequired(true)
                ->onlyOnDetail()
                ->onlyOnForms(),
        ];
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add(ConcatFilter::new(['source_username', '@', 'source_domain:domain'], 'From email'))
            ->add(ConcatFilter::new(['destination_username', '@', 'destination_domain'], 'To email'))
        ;
    }
}
