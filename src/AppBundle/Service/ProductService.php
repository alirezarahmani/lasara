<?php

namespace AppBundle\Service;

use AppBundle\Infrastructure\DataTransferObject\ProductDto;
use AppBundle\Infrastructure\Validation\ValidationInterface;
use AppBundle\Model\ProductCurrency;
use AppBundle\Model\Price;
use AppBundle\Model\Product;
use AppBundle\Infrastructure\Persistence\Doctrine\ProductRepository;
use AppBundle\Model\ProductRepositoryInterface;
use Assert\Assertion;
use Symfony\Component\HttpFoundation\Response;

class ProductService
{
    /** @var ProductRepository  */
    private $repository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->repository = $productRepository;
    }

    public function addProduct(array $inputs, ValidationInterface $validation)
    {
        $validation->validate($inputs);
        $price = new Price(
            $inputs['price']['value'],
            new ProductCurrency($inputs['price']['currency'])
        );

        $product = $this->repository->findOneBy(['name' => $inputs['name']]);
        if (!$product) {
            throw new \InvalidArgumentException(
                'product with name: ' . $inputs['name'] . ' , already Exist',
                Response::HTTP_CONFLICT
            );
        }
        $product = new Product($price, $inputs['name'], $inputs['sku']);
        $this->repository->store($product);
    }

    public function getProducts(array $inputs)
    {
        $params = [];

        if (isset($inputs['to'])) {
            Assertion::date($inputs['to'],'Y-m-d H:i:s', 'sorry, to date is wrong, make sure the format Y-m-d H:i:s should be like this');
            $params['to'] = $inputs['to'];
        }

        if (isset($inputs['from'])) {
            Assertion::date($inputs['from'],'Y-m-d H:i:s', 'sorry, from date is wrong, make sure the format Y-m-d H:i:s should be like this');
            $params['from'] = $inputs['from'];
        }

        $products = $this->repository->searchByDateCurrency($params);
        if (empty($products)) {
            return new \InvalidArgumentException('nothing found', Response::HTTP_NOT_FOUND);
        }

        // with no dto data from domain layer leaks out to application layer(if we return object)
        // then there would not be no encapsulation
        return (new ProductDto($products))->getArrayCopy(new ProductCurrency($inputs['currency'] ?? ProductCurrency::EUR));
    }
}