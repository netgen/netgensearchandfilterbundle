<?php

namespace Netgen\SearchAndFilterBundle\Components\SearchResultConverter;

use eZ\Publish\API\Repository\Repository;
use Netgen\SearchAndFilterBundle\Components\SearchAndFilterHit;
use Netgen\SearchAndFilterBundle\Components\SearchResultConverter;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
//use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;

class LegacySearchResultConverter implements SearchResultConverter {

    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    protected $repository;

    /**
     * Constructor
     */
    public function __construct( Repository $repository )
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
            $contentObject = $contentService->loadContent( $doc->ContentObjectID );
            $searchHit = new SearchAndFilterHit(
                array(
                    'score'         => 0,
                    'valueObject'   => $contentObject,
                    'objectStates'  => $this->loadContentObjectStates( $contentObject->contentInfo )
                )
            );
            $result->searchHits[] = $searchHit;
        }
        return $result;
    }

    private function loadContentObjectStates( $contentObjectInfo )
    {
        $objectStateService = $this->repository->getObjectStateService();
        $objectStateGroups = $objectStateService->loadObjectStateGroups();
        $objectStatesArray = array();
        $stripedObjectStatesGroupArray = array('ez_lock');

        foreach ( $objectStateGroups as $objectStateGroup )
        {
            if ( !in_array( $objectStateGroup->identifier, $stripedObjectStatesGroupArray ) )
            {
                $objectStatesArray[$objectStateGroup->identifier] = $objectStateService
                    ->getContentState( $contentObjectInfo, $objectStateGroup );
            }
        }

        return count($objectStatesArray) ? $objectStatesArray : null ;
    }
}