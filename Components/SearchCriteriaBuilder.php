<?php

namespace Netgen\SearchAndFilterBundle\Components;

use Symfony\Component\Form\Form;

interface SearchCriteriaBuilder {

    /**
     * Builds search criteria
     *     *
     * @return array of criteria
     */
    public function build( Form $form, $offset, $length, $params = array() );
} 