<?php

namespace App\Controllers;

use App\DD;
use App\Redirect\Redirect;
use App\Repositories\MySQLProductsRepository;
use App\Repositories\ProductsRepository;
use App\Twig\View;

class ProductsController
{
    private ProductsRepository $productsRepository;

    public function __construct()
    {
        $this->productsRepository = new MySQLProductsRepository();
    }

    public function index(): View
    {
        $products = $this->productsRepository->getAll();

        return new View('Products/products.twig', ['products' => $products]);
    }

    public function showAddProduct(): View
    {
        return new View('Products/add.twig');
    }

    public function showProduct(array $vars): View
    {
        $id = $vars['id'] ?? null;
        if($id === null) Redirect::to('/');

        $product = $this->productsRepository->getOne($id);
        return new View('Products/product.twig', ['product' => $product]);
    }

    public function showEditProduct(array $vars): View
    {
        $id = $vars['id'] ?? null;
        if($id === null) Redirect::to('/');

        $product = $this->productsRepository->getOne($id);
        return new View('Products/edit.twig', ['product' => $product]);
    }

    public function addProduct(): void
    {
        $this->productsRepository->add($_POST);
        Redirect::to('/');
    }

    public function editProduct(array $vars): void
    {
        $id = $vars['id'] ?? null;
        if($id === null) Redirect::to('/');

        $this->productsRepository->edit($_POST, $id);
        Redirect::to('/');
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
        //todo create functionality to search products based on category
        $products = $this->productsRepository->search($_GET);
        return new View('Products/products.twig', ['products' => $products]);
    }
}