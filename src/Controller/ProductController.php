<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine, ProductRepository $productRepo): JsonResponse
    {
        return $this->json($productRepo->findAll());
    }

    #[Route('/product/add', name: 'app_product_new',  methods: ['POST'])]
    public function addProduct(Request $request, ManagerRegistry $doctrine, ProductRepository $productRepo, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();
        $product = new Product();

        if (!$request->get('name') || !$request->get('stock')){
            return $this->json('Please enter the correct fields (name, stock)');
        }
        if (!is_numeric($request->get('stock'))){
            return $this->json('Please entre a number for the stock');
        }
        
        $product->setName($request->get('name'));
        $product->setStock($request->get('stock'));
        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json($productRepo->find($product->getId()));
    }

    #[Route('/product/show/{id}', name: 'app_product_show',  methods: ['GET'])]
    public function showProduct(ManagerRegistry $doctrine, int $id, ProductRepository $productRepo): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No product found for id '.$id);
        }

        return $this->json($productRepo->find($id));
    }

    #[Route('/product/edit/{id}', name: 'edit_product', methods: ['PUT'])]
    public function editProduct(Request $request, ManagerRegistry $doctrine, int $id, ProductRepository $productRepo, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $doctrine->getRepository(Product::class)->find($id);
        
        if (!$product) {
            return $this->json('No product found for id '.$id);
        }
        
        if (!$request->get('name') || !$request->get('stock')){
            return $this->json('Please enter the correct fields (name, stock)');
        }
        if (!is_numeric($request->get('stock'))){
            return $this->json('Please entre a number for the stock');
        }

        $product->setName($request->get('name'));
        $product->setStock($request->get('stock'));

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json($productRepo->find($product->getId()));
    }

    #[Route('/product/delete/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function deleteProduct(ManagerRegistry $doctrine, int $id, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No product found for id '.$id);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json('Product '.$id.' is delete');
    }

    #[Route('/product/editStock/{id}', name: 'app_editStock_product', methods: ['PUT'])]
    public function editStockProduct(Request $request, ManagerRegistry $doctrine, int $id, ProductRepository $productRepo): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json('No product found for id '.$id);
        }
        if (!is_numeric($request->get('stock'))){
            return $this->json('Please entre a number for the stock');
        }

        $product->setStock($product->getStock() + $request->get('editStock'));

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json($productRepo->find($product->getId()));
    }
}
