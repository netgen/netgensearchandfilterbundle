<?php

namespace Netgen\SearchAndFilterBundle\Components\SearchResultConverter;

use Netgen\SearchAndFilterBundle\Components\SearchResultConverter;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;

class LegacySearchResultConverter implements SearchResultConverter {

    protected $repository;

    /**
     * Constructor

     */
    public function __construct( $repository )
    {
        $this->repository = $repository;
    }

    /**
     * Builds search result
     */
    public function convert( $input ) {

        $result = new SearchResult(
            array(
                'time'       => 0,
                'maxScore'   => 0,
                'totalCount' => $input['SearchCount'],
            )
        );

        $contentService = $this->repository->getContentService();
        foreach ( $input['SearchResult'] as $doc )
        {
            $searchHit = new SearchHit(
                array(
                    'score'       => 0,
                    'valueObject' => $contentService->loadContent( $doc->ContentObjectID )
                )
            );
            $result->searchHits[] = $searchHit;
        }
        return $result;

    }

} 