<?php

namespace App\Controllers;

use App\Redirect\Redirect;
use App\Repositories\MySQLProductsRepository;
use App\Repositories\ProductsRepository;
use App\Twig\View;
use App\Validation\FormValidationException;
use App\Validation\ProductsValidator;

class ProductsController extends ProductsValidator
{
    private ProductsRepository $productsRepository;

    public function __construct()
    {
        $this->productsRepository = new MySQLProductsRepository();
    }

    public function index(): View
    {
        if(!isset($_SESSION['id'])){
            return new View('Users/login.twig');
        }
        $products = $this->productsRepository->getAll($_SESSION['id']);
        return new View('Products/products.twig', ['products' => $products]);
    }

    public function showAddProduct(): View
    {
        if(!isset($_SESSION['id'])) Redirect::to('/');
        return new View('Products/add.twig');
    }

    public function showProduct(array $vars): View
    {
        $id = $vars['id'] ?? null;
        if($id === null) Redirect::to('/');

        $product = $this->productsRepository->getOne($id, $_SESSION['id']);
        return new View('Products/product.twig', ['product' => $product]);
    }

    public function showEditProduct(array $vars): View
    {
        $id = $vars['id'] ?? null;
        if($id === null) Redirect::to('/');

        $product = $this->productsRepository->getOne($id, $_SESSION['id']);
        return new View('Products/edit.twig', ['product' => $product]);
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
            Redirect::to('/');
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

    public function searchByCategory(): View
    {
        try {
            $this->validateSearch($_GET);
            $products = $this->productsRepository->search($_GET['category'], $_SESSION['id']);
        } catch (FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->getErrors();
            Redirect::to('/');
        }
        return new View('Products/products.twig', ['products' => &$products]);
    }
}