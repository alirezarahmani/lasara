<?php
declare(strict_types = 1);

namespace AppBundle\Controller;

use AppBundle\Infrastructure\Request\ApiApplicationRequest;
use AppBundle\Infrastructure\Request\ApiResponseInterface;
use AppBundle\Infrastructure\Validation\ProductApiInputValidation;
use AppBundle\Model\ProductRepositoryInterface;
use AppBundle\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductsController
 */
class ProductsController extends Controller
{
    /** @var ProductService */
    private $productService;

    /**
     * ProductsController constructor.
     *
     * @param ProductService $service
     */
    public function __construct(ProductService $service)
    {
        $this->productService = $service;
    }

    /**
     * @param ApiApplicationRequest $request
     * @param ApiResponseInterface  $apiJsonResponse
     * @Route("/products" , name="create_products", methods={"POST"})
     */
    public function create(ApiApplicationRequest $request, ApiResponseInterface $apiJsonResponse)
    {
        try {
            $this->productService->addProduct($request->getRequest()->query->all(), new ProductApiInputValidation());
            $apiJsonResponse->success();
        } catch (\InvalidArgumentException $exception) {
            $apiJsonResponse->error($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param ApiApplicationRequest $request
     * @param ApiResponseInterface  $apiJsonResponse
     * @Route("/products/search" , name="search_products", methods={"GET"})
     */
    public function search(ApiApplicationRequest $request, ApiResponseInterface $apiJsonResponse)
    {
        try {
            $products = $this->productService->getProducts($request->getRequest()->query->all());
            $apiJsonResponse->success($products);
        } catch (\InvalidArgumentException $exception) {
            $apiJsonResponse->error($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param ProductRepositoryInterface $productRepository
     * @return Response
     * @Route("/products" , name="get_all_products", methods={"GET"})
     */
    public function getAll(ProductRepositoryInterface $productRepository)
    {
        return $this->render('default/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
}
