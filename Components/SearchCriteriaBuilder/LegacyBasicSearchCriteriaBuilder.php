<?php

namespace Netgen\SearchAndFilterBundle\Components\SearchCriteriaBuilder;

use Netgen\SearchAndFilterBundle\Components\SearchCriteriaBuilder;
use Symfony\Component\Form\Form;

class LegacyBasicSearchCriteriaBuilder implements SearchCriteriaBuilder {

    /**
     * Builds search criteria
     *
     * @return criteria array
     */
    public function build( Form $form, $offset, $length, $params = array() ) {

        $data = $form->getData();

        if ($length == 0)
            return array( 'text' => $data['term'], 'sort_by' => array( 'name' => true ), 'offset' => $offset );
        else
            return array( 'text' => $data['term'], 'sort_by' => array( 'name' => true ), 'offset' => $offset, 'limit' => $length );
    }

} 