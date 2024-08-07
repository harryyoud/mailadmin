<?php

namespace App\Controller;

use App\Entity\Alias;
use App\Filter\ConcatFilter;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;

class AliasCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string {
        return Alias::class;
    }

    public function createEntity(string $entityFqcn) {
        return (new Alias())
            ->setCanSend(true)
            ->setCanReceive(true)
        ;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud
            ->setEntityLabelInPlural('Aliases')
            ->setEntityLabelInSingular('Alias')
        ;
    }

    public function configureFields(string $pageName): iterable {
        return [
            FormField::addPanel(),
            Field::new('can_send')
                ->setHelp("Allows the destination user to be able to send from this alias")
                ->setColumns(3),
            Field::new('can_receive')
                ->setHelp("Forwards the source user mail to destination user. If source user is a mailbox, a copy will be stored in that mailbox")
                ->setColumns(3),

            FormField::addPanel('From'),
            Field::new('source_address', 'From')
                ->onlyOnIndex(),
            Field::new('source_username', 'Username')
                ->setRequired(false)
                ->setHelp("Leaving this blank is the same as a catchall")
                ->onlyOnDetail()
                ->onlyOnForms()
                ->setColumns(6),
            Field::new('source_domain', 'Domain')
                ->setRequired(false)
                ->setHelp("Leaving this blank is the same as a wildcard (for e.g. postmaster@)")
                ->onlyOnDetail()
                ->onlyOnForms()
                ->setColumns(6),

            FormField::addPanel('To'),
            Field::new('destination_address', 'To')
                ->onlyOnIndex(),
            Field::new('destination_username', 'Username')
                ->setRequired(true)
                ->onlyOnDetail()
                ->onlyOnForms()
                ->setColumns(6),
            Field::new('destination_domain', 'Domain')
                ->setRequired(true)
                ->onlyOnDetail()
                ->onlyOnForms()
                ->setColumns(6),
            
            FormField::addPanel(),
            Field::new('comment')
                ->setHelp("Free text, no consequence comment")
                ->setColumns(12)
                ->setRequired(false)
                ->setEmptyData('')
        ];
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add(ConcatFilter::new(['source_username', '@', 'source_domain:domain'], 'From email'))
            ->add(ConcatFilter::new(['destination_username', '@', 'destination_domain'], 'To email'))
        ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder {
        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->addOrderBy('entity.source_domain', 'ASC')
            ->addOrderBy('entity.source_username', 'ASC')
            ->addOrderBy('entity.destination_domain', 'ASC')
            ->addOrderBy('entity.destination_username', 'ASC')
        ;
    }
}
