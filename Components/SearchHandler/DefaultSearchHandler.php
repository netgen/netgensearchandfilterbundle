<?php

namespace Netgen\SearchAndFilterBundle\Components\SearchHandler;

use Symfony\Component\Form\Form;
use Netgen\SearchAndFilterBundle\Components\SearchHandler;
use Netgen\SearchAndFilterBundle\Components\SearchCriteriaBuilder;
use Netgen\SearchAndFilterBundle\Components\SearchResultConverter;

class DefaultSearchHandler implements SearchHandler {

    protected $repository;

    /**
     * Constructor
     */
    public function __construct( $repository )
    {
        $this->repository = $repository;
    }
    /**
     * Executes the search
     *
     * @return result array
     */
    public function search( Form $form, SearchCriteriaBuilder $searchCriteriaBuilder, SearchResultConverter $resultConverter, $offset, $length ) {

        $criteria = $searchCriteriaBuilder->build($form, $offset, $length);

        $searchResults = $this->repository->getSearchService()->findContent( $criteria );

        return $resultConverter->convert( $searchResults );
    }

    /**
     * Return total search result count
     * @return count
     */
    public function searchCount( Form $form, SearchCriteriaBuilder $searchCriteriaBuilder ) {

        $criteria = $searchCriteriaBuilder->build($form, 0, 0);

        $searchResults = $this->repository->getSearchService()->findContent( $criteria );

        return $searchResults->totalCount;
    }
} 