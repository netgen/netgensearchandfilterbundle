<?php

namespace Netgen\SearchAndFilterBundle\Components;

interface SearchResultConverter {

    /**
     * Builds result
     */
    public function convert( $input );
} 