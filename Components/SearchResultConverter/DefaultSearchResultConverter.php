<?php

namespace Netgen\SearchAndFilterBundle\Components\SearchResultConverter;

use Netgen\SearchAndFilterBundle\Components\SearchResultConverter;

class DefaultSearchResultConverter implements SearchResultConverter {

    /**
     * Builds search result
     */
    public function convert( $input ) {

        return $input;
    }

} 