<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\Documentation\ApiFactory;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class SwaggerUiController extends AbstractActionController
{
    /** @var ApiFactory */
    protected $apiFactory;

    public function __construct(ApiFactory $apiFactory)
    {
        $this->apiFactory = $apiFactory;
    }

    /**
     * List available APIs
     *
     * @return ViewModel
     */
    public function listAction()
    {
        $apis = $this->apiFactory->createApiList();

        $viewModel = new ViewModel(['apis' => $apis]);
        $viewModel->setTemplate('api-tools-documentation-swagger/list');
        return $viewModel;
    }

    /**
     * Show the Swagger UI for a given API
     *
     * @return ViewModel
     */
    public function showAction()
    {
        $api = $this->params()->fromRoute('api');

        $viewModel = new ViewModel(['api' => $api]);
        $viewModel->setTemplate('api-tools-documentation-swagger/show');
        $viewModel->setTerminal(true);
        return $viewModel;
    }
}
