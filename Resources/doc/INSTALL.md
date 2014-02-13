Netgen Search And Filter Bundle installation instructions
=========================================================

Requirements
------------

* eZ Publish 5.2+ / eZ Publish Community Project 2013.11+

### Note

For using legacy handlers a legacy stack needs to be up and running, for ezfind handler the ezfind extension needs to be up and running on legacy stack

Installation steps
------------------

### Use Composer

Add the following to your composer.json and run `php composer.phar update` to refresh dependencies:

```json
"require": {
    "netgen/searchandfilter-bundle": "0.1"
}
```

### Activate the bundle

Activate the bundle in `ezpublish\EzPublishKernel.php` file.

```php
use Netgen\SearchAndFilterBundle\NetgenSearchAndFilterBundle(),

...

public function registerBundles()
{
   $bundles = array(
       new FrameworkBundle(),
       ...
       new NetgenSearchAndFilterBundle()
   );

   ...
}
```

### Edit configuration

Put the following config in your `ezpublish/config/parameters.yml` file to set the main pagelayout template.

```yml
netgen_search_and_filter:
    main_pagelayout: "YOUR_MAIN_PAGELAYOUT_TWIG_TEMPLATE"
```

Be sure to replace `YOUR_MAIN_PAGELAYOUT_TWIG_TEMPLATE`, e.g. "YourProjectBundle::pagelayout.html.twig"

### Use the bundle with basic search

There are 2 ways to use the service

1) with a route (e.g. /search/example)

In your routing.yml put:
```yml
netgen_search_example:
    path: /search/{context}
    defaults:  { _controller: NetgenSearchAndFilterBundle:Search:searchRoute, context: null }
    requirements:
        locationId:  \s
```

In your services.yml configure the service (in this example we are using eZ5 Public API search with page limit of 5)
```yml
services:
    netgen_search_and_filter.route_example:
        class: Netgen\SearchAndFilterBundle\Components\SearchAdapter
        arguments:
            - @netgen_search_and_filter.handler.default
            - @netgen_search_and_filter.form_type.basic_search_form_type
            - @form.factory
            - @netgen_search_and_filter.criteria_builder.default_basic_search
            - @netgen_search_and_filter.result_converter.default
            - NetgenSearchAndFilterBundle::route_search_results.html.twig
            - NetgenSearchAndFilterBundle:forms:basic_search.html.twig
            - 5
```

2) overriding a location or content type with the Search And Filter action

In your ezpublish.yml put for example an override for location id 123:
```yml
ezpublish:
    system:
        ezdemo_site_clean_group:
            location_view:
                full:
                    netgen_search_and_filter_set:
                        controller: NetgenSearchAndFilterBundle:Search:searchLocation
                        match:
                            Identifier\Location: 123
```

In your services.yml configure the service (in this example we are using legacy search with default page limit)
```yml
    netgen_search_filter.location_123:
        class: Netgen\SearchAndFilterBundle\Components\SearchAdapter
        arguments:
            - @netgen_search_filter.handler.legacy
            - @netgen_search_filter.form_type.basic_search_form_type
            - @form.factory
            - @netgen_search_filter.criteria_builder.legacy_basic_search
            - @netgen_search_filter.result_converter.legacy
            - NetgenSearchAndFilterBundle::location_search_results.html.twig
            - NetgenSearchAndFilterBundle:forms:basic_search.html.twig
            - %netgen_search_filter.page_limit%
```
If the controller doesn't find the service it will silently fall back to normal view

### Custom searching and filtering

The absolute minimum are the following steps:

1. Build the form type with Symfony forms (example Form/Type/BasicSearchType.php) and make it as service - YOUR_FORM_TYPE_SERVICE

2. Decide are using eZ Publish 5 public API based search, standard legacy based search or ezfind legacy based search

3. Based on that decision create the custom criteria builder and make it as service - YOUR_CRITERIA_BUILDER_SERVICE (examples in Components/SearchCriteriaBuilder)

4. Create the form template - YOUR_FORM_TEMPLATE (example in Resources/views/forms/basic_search.html.twig)

5. Create the search service to be used by the controllers:
```yml
    netgen_search_filter.[location|route]_[locationid|context]:
        class: Netgen\SearchAndFilterBundle\Components\SearchAdapter
        arguments:
            - @netgen_search_filter.handler.[default|legacy|ezfind]
            - YOUR_FORM_TYPE_SERVICE
            - @form.factory
            - YOUR_CRITERIA_BUILDER_SERVICE
            - @netgen_search_filter.result_converter.[default|legacy]
            - NetgenSearchAndFilterBundle::[location|route]_search_results.html.twig
            - YOUR_FORM_TEMPLATE
            - %netgen_search_filter.page_limit%
```

6. Create the route or the override so that controller action can be called

Optionally you can also:
* make your own handler
* make your own result converter
* make your own search result template

