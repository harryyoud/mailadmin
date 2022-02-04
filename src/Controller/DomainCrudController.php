<?php

namespace App\Controller;

use App\Entity\Domain;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DomainCrudController extends AbstractCrudController {
    public static function getEntityFqcn(): string {
        return Domain::class;
    }

    public function configureFields(string $pageName): iterable {
        return [
            TextField::new('domain')
        ];
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInPlural('Domains')
            ->setEntityLabelInSingular('Domain')
        ;
    }
}
