<?php

namespace Netgen\SearchAndFilterBundle\Components;

use Pagerfanta\Adapter\AdapterInterface;

class SearchAdapter implements AdapterInterface {

    protected $handler;
    protected $criteriaBuilder;
    protected $form;
    protected $resultConverter;
    protected $resultTemplate;
    protected $formTemplate;
    protected $pageLimit;

    private $nbResults;

    /**
     * Constructor
     *
     */
    public function __construct( $handler, $formType, $formFactory, $criteriaBuilder, $resultConverter, $resultTemplate, $formTemplate, $pageLimit )
    {
        $this->handler = $handler;
        $this->form = $formFactory->createBuilder($formType)->getForm();
        $this->criteriaBuilder = $criteriaBuilder;
        $this->resultConverter = $resultConverter;
        $this->resultTemplate = $resultTemplate;
        $this->formTemplate = $formTemplate;
        $this->pageLimit = $pageLimit;
    }

    public function getForm() {
        return $this->form;
    }

    public function getResultTemplate( ) {
        return $this->resultTemplate;
    }

    public function getFormTemplate( ) {
        return $this->formTemplate;
    }

    public function getPageLimit( ) {
        return $this->pageLimit;
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    public function getNbResults()
    {

        if ( isset( $this->nbResults ) )
        {
            return $this->nbResults;
        }
        $this->nbResults = $this->handler->searchCount( $this->form, $this->criteriaBuilder );
        return $this->nbResults;
    }

    /**
     * Returns as slice of the results, as SearchHit objects.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Search\SearchHit The slice.
     */
    public function getSlice( $offset, $length )
    {
        $searchResult = $this->handler->search( $this->form, $this->criteriaBuilder, $this->resultConverter, $offset, $length );
        if ( !isset( $this->nbResults ) )
        {
            $this->nbResults = $searchResult->totalCount;
        }

        return $searchResult->searchHits;
    }
} 