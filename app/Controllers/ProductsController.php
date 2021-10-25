<?php

namespace App\Controllers;

use App\DD;
use App\Messages\Messages;
use App\Redirect\Redirect;
use App\Repositories\ProductsRepository\ProductsRepository;
use App\Repositories\TagsRepository\MySQLTagsRepository;
use App\Repositories\TagsRepository\TagsRepository;
use App\Services\AddMessages\AddMessageService;
use App\Services\ProductServices\AddProduct\AddProductRequest;
use App\Services\ProductServices\AddProduct\AddProductService;
use App\Services\ProductServices\DeleteProduct\DeleteProductRequest;
use App\Services\ProductServices\DeleteProduct\DeleteProductService;
use App\Services\ProductServices\EditProduct\EditProductRequest;
use App\Services\ProductServices\EditProduct\EditProductService;
use App\Services\ProductServices\GetProduct\GetProductRequest;
use App\Services\ProductServices\GetProduct\GetProductService;
use App\Services\ProductServices\ListAllProducts\ListAllProductsRequest;
use App\Services\ProductServices\ListAllProducts\ListAllProductsService;
use App\Services\ProductServices\Search\SearchByTagsRequest;
use App\Services\ProductServices\Search\SearchByTagsService;
use App\Services\ProductServices\Search\SearchService;
use App\Services\ProductServices\Search\SearchServiceRequest;
use App\Services\TagServices\ListAllTags\ListAllTagsService;
use App\Services\TagServices\ListProductTags\ListProductTagsRequest;
use App\Services\TagServices\ListProductTags\ListProductTagsService;
use App\Twig\View;
use App\Validation\FormValidationException;
use App\Validation\ProductsValidator;

class ProductsController extends ProductsValidator
{
    private ProductsRepository $productsRepository;
    private ListAllProductsService $listAllProductsService;
    private AddMessageService $addMessageService;
    private AddProductService $addProductService;
    private ListAllTagsService $listAllTagsService;
    private ListProductTagsService $listProductTagsService;
    private GetProductService $getProductService;
    private EditProductService $editProductService;
    private DeleteProductService $deleteProductService;
    private SearchService $searchService;
    private SearchByTagsService $searchByTagsService;

    public function __construct(
        ProductsRepository $productsRepository,
        ListAllProductsService $listAllProductsService,
        AddMessageService $addMessageService,
        AddProductService $addProductService,
        ListAllTagsService $listAllTagsService,
        ListProductTagsService $listProductTagsService,
        GetProductService $getProductService,
        EditProductService $editProductService,
        DeleteProductService $deleteProductService,
        SearchService $searchService,
        SearchByTagsService $searchByTagsService
    )
    {
        $this->productsRepository = $productsRepository;
        $this->listAllProductsService = $listAllProductsService;
        $this->addMessageService = $addMessageService;
        $this->addProductService = $addProductService;
        $this->listAllTagsService = $listAllTagsService;
        $this->listProductTagsService = $listProductTagsService;
        $this->getProductService = $getProductService;
        $this->editProductService = $editProductService;
        $this->deleteProductService = $deleteProductService;
        $this->searchService = $searchService;
        $this->searchByTagsService = $searchByTagsService;
    }

    public function index(): View
    {
        if(!isset($_SESSION['id'])){
            return new View('Users/login.twig');
        }

        $products = $this->listAllProductsService->execute(new ListAllProductsRequest($_SESSION));
        $tags = $this->listAllTagsService->execute();
        return new View('Products/products.twig', ['products' => $products, 'tags' => $tags]);
    }

    private function tags(): TagsRepository
    {
        return new MySQLTagsRepository();
    }

    public function showAddProduct(): View
    {
        if(!isset($_SESSION['id'])) Redirect::to('/');

        $tags = $this->listAllTagsService->execute();
        return new View('Products/add.twig', ['tags' => $tags]);
    }

    public function showProduct(array $vars): View
    {
        $id = $this->productExists($vars);

        $product = $this->getProductService->execute(new GetProductRequest($id, $_SESSION));
        $tags = $this->listProductTagsService->execute(new ListProductTagsRequest($vars['id']));
        return new View('Products/product.twig', ['product' => $product, 'tags' => $tags]);
    }

    public function showEditProduct(array $vars): View
    {
        $id = $this->productExists($vars);

        $product = $this->getProductService->execute(new GetProductRequest($id, $_SESSION));
        $tags = $this->listProductTagsService->execute(new ListProductTagsRequest($vars['id']));
        $allTags = $this->listAllTagsService->execute();
        return new View('Products/edit.twig', ['product' => $product, 'tags' => $tags, 'allTags' => $allTags]);
    }

    public function addProduct(): void
    {
        try{
            $productExists = $this->addProductService->checkIfAvailable(new AddProductRequest($_POST, $_SESSION));
            $this->validateProduct($_POST, $productExists);

            $this->addProductService->execute(new AddProductRequest($_POST, $_SESSION));
            $this->addMessageService->add(Messages::PRODUCT_ADD_SUCCESS);
            Redirect::to('/add');

        } catch(FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->getErrors();
            Redirect::to('/add');
        }
    }

    public function editProduct(array $vars): void
    {
        $id = $vars['id'] ?? null;
        if($id === null) Redirect::to('/');

        try {
            $this->validateProduct($_POST);

            $this->editProductService->execute(new EditProductRequest($_POST, $id));
            $this->addMessageService->add(Messages::PRODUCT_UPDATE_SUCCESS);
            $location = "/product/" . $id;
            Redirect::to($location);
        } catch(FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->getErrors();
            $location = "/edit/" . $id;
            Redirect::to($location);
        }
    }

    public function deleteProduct(array $vars): void
    {
        $id = $vars['id'] ?? null;
        if($id === null) Redirect::to('/');

        $this->deleteProductService->execute(new DeleteProductRequest($id));
        $this->addMessageService->add(Messages::PRODUCT_DELETE_SUCCESS);
        Redirect::to('/');
    }

    public function searchProducts(): View
    {
        try {
            $this->validateSearch($_GET);
            $products = $this->searchService->execute(new SearchServiceRequest($_GET, $_SESSION));
        } catch (FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->getErrors();
            Redirect::to('/');
        }
        $tags = $this->listAllTagsService->execute();
        return new View('Products/products.twig', ['products' => &$products, 'tags' => $tags]);
    }

    public function searchByTags(): View
    {
        if(!isset($_GET['tags'])) Redirect::to('/');
        $products = $this->searchByTagsService->execute(new SearchByTagsRequest($_GET));
        $tags = $this->listAllTagsService->execute();
        return new View('Products/products.twig', ['products' => &$products, 'tags' => $tags]);
    }

    private function productExists(array $vars): string
    {
        $id = $vars['id'] ?? null;
        if($id === null || $_SESSION['id'] === null) Redirect::to('/');
        return $id;
    }
}