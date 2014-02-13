<?php

namespace Netgen\SearchAndFilterBundle\Components;

use Symfony\Component\Form\Form;
use Netgen\SearchAndFilterBundle\Components\SearchCriteriaBuilder;
use Netgen\SearchAndFilterBundle\Components\SearchResultConverter;

interface SearchHandler {

    /**
     * Does a search
     *
     */
    public function search( Form $form, SearchCriteriaBuilder $searchCriteriaBuilder, SearchResultConverter $resultConverter, $offset, $length, $params = array());

    /**
     * Return total search result count
     *
     */
    public function searchCount( Form $form, SearchCriteriaBuilder $searchCriteriaBuilder, $params = array());

} 