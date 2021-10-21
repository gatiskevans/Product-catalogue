<?php

namespace App\Controllers;

use App\Redirect\Redirect;
use App\Repositories\MySQLProductsRepository;
use App\Repositories\MySQLTagsRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\TagsRepository;
use App\Twig\View;
use App\Validation\FormValidationException;
use App\Validation\ProductsValidator;

class ProductsController extends ProductsValidator
{
    private ProductsRepository $productsRepository;

    public function __construct(array $container)
    {
        $this->productsRepository = $container[ProductsRepository::class];
    }

    public function index(): View
    {
        if(!isset($_SESSION['id'])){
            return new View('Users/login.twig');
        }
        $products = $this->productsRepository->getAll($_SESSION['id']);
        $tags = $this->tags()->getAll();
        return new View('Products/products.twig', ['products' => $products, 'tags' => $tags]);
    }

    private function tags(): TagsRepository
    {
        return new MySQLTagsRepository();
    }

    public function showAddProduct(): View
    {
        if(!isset($_SESSION['id'])) Redirect::to('/');
        $tags = $this->tags()->getAll();
        return new View('Products/add.twig', ['tags' => $tags]);
    }

    public function showProduct(array $vars): View
    {
        $id = $this->productExists($vars);

        $product = $this->productsRepository->getOne($id, $_SESSION['id']);
        $tags = $this->tags()->getProductTags($vars['id']);
        return new View('Products/product.twig', ['product' => $product, 'tags' => $tags]);
    }

    public function showEditProduct(array $vars): View
    {
        $id = $this->productExists($vars);

        $product = $this->productsRepository->getOne($id, $_SESSION['id']);
        $tags = $this->tags()->getProductTags($vars['id']);
        $allTags = $this->tags()->getAll();
        return new View('Products/edit.twig', ['product' => $product, 'tags' => $tags, 'allTags' => $allTags]);
    }

    public function addProduct(): void
    {
        try{
            $productExists = $this->productsRepository->getByTitle($_POST, $_SESSION['id']);
            $this->validateProduct($_POST, $productExists);

            $this->productsRepository->add($_POST, $_SESSION['id']);
            $_SESSION['message'] = "Product Added Successfully!";
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

            $this->productsRepository->edit($_POST, $id);
            $_SESSION['message'] = "Edited Successfully!";
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

        $this->productsRepository->delete($id);
        Redirect::to('/');
    }

    public function searchProducts(): View
    {
        try {
            $this->validateSearch($_GET);
            $products = $this->productsRepository->search($_GET['category'], $_SESSION['id'], $_GET['sort']);
        } catch (FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->getErrors();
            Redirect::to('/');
        }
        $tags = $this->tags()->getAll();
        return new View('Products/products.twig', ['products' => &$products, 'tags' => $tags]);
    }

    public function searchBYTags(): View
    {
        if(!isset($_GET['tags'])) Redirect::to('/');
        $products = $this->productsRepository->searchByTags($_GET['tags'], $_SESSION['id']);
        $tags = $this->tags()->getAll();
        return new View('Products/products.twig', ['products' => &$products, 'tags' => $tags]);
    }

    private function productExists(array $vars): string
    {
        $id = $vars['id'] ?? null;
        if($id === null || $_SESSION['id'] === null) Redirect::to('/');
        return $id;
    }
}