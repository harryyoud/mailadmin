<?php


namespace App\Filter;


use Doctrine\Common\Util\Debug;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\TextFilterType;

class ConcatFilter implements FilterInterface {

    private const NEST_SEPARATOR = ':';
    private const PROP_SEPARATOR = '_CONCAT_';

    use FilterTrait;

    /**
     * @param array $props
     * @param null $label
     * @return static
     */
    public static function new(array $props, $label = null): self {
        return (new self())
            ->setFilterFqcn(__CLASS__)
            ->setProperty(rtrim(strtr(base64_encode(implode(self::PROP_SEPARATOR, $props)), '+/', '-_'), '='))
            ->setLabel($label)
            ->setFormType(TextFilterType::class);
    }

    public function apply(QueryBuilder $queryBuilder, FilterDataDto $filterDataDto, ?FieldDto $fieldDto, EntityDto $entityDto): void {
        $alias = $filterDataDto->getEntityAlias();
        $props = explode(self::PROP_SEPARATOR, base64_decode(str_pad(strtr($filterDataDto->getProperty(), '-_', '+/'), strlen($filterDataDto->getProperty()) % 4, '=', STR_PAD_RIGHT)));
        $comparison = $filterDataDto->getComparison();
        $parameterName = $filterDataDto->getParameterName();
        $value = $filterDataDto->getValue();

        $parameterName = $this->sanitizeParam($parameterName);

        $concat_items = [];

        foreach ($props as $prop) {
            if ($entityDto->hasProperty($prop) || $entityDto->hasProperty(explode(self::NEST_SEPARATOR, $prop)[0])) {
                $concat_items[] = $this->processNestedProp($prop, $queryBuilder, $alias);
            } else {
                $concat_items[] = "'".$prop."'";
            }
        }

        $queryBuilder->andWhere(sprintf('%s %s :%s',
            $queryBuilder->expr()->concat(...$concat_items)->__toString(),
            $comparison,
            $parameterName,
        ))->setParameter($parameterName, $value);
    }

    private static function sanitizeParam(string $raw) {
        return preg_replace("/[^a-zA-Z0-9_]+/", "", $raw);
    }

    private function processNestedProp(string $raw, QueryBuilder $queryBuilder, string $alias) {
        $items = explode(self::NEST_SEPARATOR, $raw);
        if (count($items) > 2) {
            throw new \ValueError("Cannot have more nested property deeper than 1 level");
        }
        if (count($items) == 2) {
            $new_alias = $items[0].'_j_0';
            $queryBuilder->leftJoin($alias.'.'.$items[0], $new_alias);
            return $new_alias.'.'.$items[1];
        }
        if (count($items) == 1) {
            return $alias.'.'.$items[0];
        }
        throw new \ValueError("Must have a non-blank property name");
    }
}