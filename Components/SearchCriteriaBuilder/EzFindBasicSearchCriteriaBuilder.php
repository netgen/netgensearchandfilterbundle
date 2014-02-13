<?php

namespace Netgen\SearchAndFilterBundle\Components\SearchCriteriaBuilder;

use Netgen\SearchAndFilterBundle\Components\SearchCriteriaBuilder;
use Symfony\Component\Form\Form;

class EzFindBasicSearchCriteriaBuilder implements SearchCriteriaBuilder {

    /**
     * Builds search criteria
     *
     * @return criteria array
     */
    public function build( Form $form, $offset, $length ) {

        $data = $form->getData();

        if ($length == 0)
            return array( 'query' => $data['term'], 'sort_by' => array( 'name' => 'asc' ), 'offset' => $offset );
        else
            return array( 'query' => $data['term'], 'sort_by' => array( 'name' => 'asc' ), 'offset' => $offset, 'limit' => $length );
    }

} 