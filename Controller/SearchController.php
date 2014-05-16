<?php

namespace Netgen\SearchAndFilterBundle\Controller;

use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\API\Repository\Values\Content\Query;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Pagerfanta\Pagerfanta;
use Netgen\SearchAndFilterBundle\Components\SearchAdapter;

class SearchController extends Controller
{


    /**
     * Action for rendering search page for Route
     */
    public function searchRouteAction( $context )
    {
        /** @var SearchAdapter $searchAdapter */
        $searchAdapter = $this->get('netgen_search_and_filter.route_'.$context);

        /** @var Form $form */
        $form = $searchAdapter->getForm();

        $response = new Response();

        $pager = new Pagerfanta( $searchAdapter );
        $pager->setMaxPerPage( $searchAdapter->getPageLimit() );

        $showResults = $this->getPage( $pager, $form );

        $request = $this->container->get('request');

        return $this->render($searchAdapter->getResultTemplate(), array(
            'pagelayout' => $this->container->getParameter("netgen_search_and_filter.main_pagelayout"),
            'route' => $request->get('_route'),
            'context' => $context,
            'form_template' => $searchAdapter->getFormTemplate(),
            'form' => $form->createView(),
            'pager' => $pager,
            'show_results' => $showResults,
            'query_string' => array($form->getName() => $form->getData()),
            'current_locale' => $this->getConfigResolver()->getParameter('RegionalSettings.Locale')
        ), $response);
    }

    /**
     * Action for rendering search page for Location
     */
    public function searchLocationAction( $locationId, $viewType, $layout = false, array $params = array() )
    {
        $repository = $this->getRepository();
        $location = $repository->getLocationService()->loadLocation( $locationId );

        try {
            /** @var SearchAdapter $searchAdapter */
            $searchAdapter = $this->get('netgen_search_and_filter.location_'.$locationId);
        } catch (ServiceNotFoundException $e) {
            return $this->get( 'ez_content' )->viewLocation( $locationId, $viewType, $layout, $params );
        }

        /** @var Form $form */
        $form = $searchAdapter->getForm();

        $response = new Response();
        $response->headers->set( 'X-Location-Id', $locationId );
        $response->setVary( 'X-User-Hash' );

        $pager = new Pagerfanta( $searchAdapter );
        $pager->setMaxPerPage( $searchAdapter->getPageLimit() );

        $showResults = $this->getPage( $pager, $form );

        return $this->render($searchAdapter->getResultTemplate(), array(
            'pagelayout' => $this->container->getParameter("netgen_search_and_filter.main_pagelayout"),
            'form_template' => $searchAdapter->getFormTemplate(),
            'form' => $form->createView(),
            'location' => $location,
            'content' => $repository->getContentService()->loadContentByContentInfo( $location->getContentInfo() ),
            'pager' => $pager,
            'show_results' => $showResults,
            'query_string' => array($form->getName() => $form->getData()),
            'current_locale' => $this->getConfigResolver()->getParameter('RegionalSettings.Locale')
        ), $response);

    }

    /**
     * handles request, sets the page, gets data for the page, returns boolean to show or not the results
     */
    public function getPage( $pager, $form ) {
        $showResults = false;

        if ($this->getRequest()->isMethod("POST")) {
            $form->handleRequest( $this->getRequest() );

            if ( $form->isValid() )
            {
                $pager->setCurrentPage( $this->getRequest()->get( 'page', 1 ) );
                $showResults = true;
            }

        } else {
            $data = $this->getRequest()->query->all();
            if (array_key_exists($form->getName(), $data))
            {

                $form->setData($data[$form->getName()]);

                if (is_numeric($this->getRequest()->query->get("page")))
                {
                    $pager->setCurrentPage( $this->getRequest()->get( 'page', $this->getRequest()->query->get("page") ) );
                    $showResults = true;
                }
            }
        }

        return $showResults;
    }

}