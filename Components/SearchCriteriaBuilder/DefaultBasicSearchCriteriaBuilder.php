<?php

namespace Netgen\SearchAndFilterBundle\Components\SearchCriteriaBuilder;

use Netgen\SearchAndFilterBundle\Components\SearchCriteriaBuilder;
use Symfony\Component\Form\Form;
use eZ\Publish\API\Repository\Values\Content\Query;

class DefaultBasicSearchCriteriaBuilder implements SearchCriteriaBuilder {

    /**
     * Builds search criteria
     *
     * @return criteria array
     */
    public function build( Form $form, $offset, $length, $params = array() ) {

        $data = $form->getData();

        $query = new Query();
        $query->criterion = new Query\Criterion\Field( 'name', Query\Criterion\Operator::LIKE, "%".$data['term']."%" );
        $query->sortClauses = array( new Query\SortClause\ContentName( Query::SORT_ASC ) );
        $query->limit = $length;
        $query->offset = $offset;

        return $query;
    }

} 