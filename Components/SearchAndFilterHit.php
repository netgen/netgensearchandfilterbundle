<?php
namespace Netgen\SearchAndFilterBundle\Components;

use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;

class SearchAndFilterHit extends SearchHit
{
    /**
     * @var array
     */
    public $objectStates;
}