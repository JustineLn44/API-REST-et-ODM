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
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine, ProductRepository $productRepo): JsonResponse
    {
        return $this->json($productRepo->findAll());
    }

    #[Route('/product/add', name: 'app_product_new',  methods: ['POST'])]
    public function addProduct(Request $request, ManagerRegistry $doctrine, ProductRepository $productRepo): Response
    {
        $entityManager = $doctrine->getManager();
        $product = new Product();
        $product->setName($request->get('name'));
        $product->setStock($request->get('stock'));

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
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->json($productRepo->find($id));
    }

    #[Route('/product/edit/{id}', name: 'edit_product', methods: ['GET', 'POST'])]
    public function editProduct(Request $request, ManagerRegistry $doctrine, int $id, ProductRepository $productRepo): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $product->setName($request->get('name'));
        $product->setStock($request->get('stock'));

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json($productRepo->find($product->getId()));
    }

    #[Route('/product/delete/{id}', name: 'app_product_delete', methods: ['GET', 'POST'])]
    public function deleteProduct(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json('Product '.$id.' is delete');
    }

    #[Route('/product/editStock/{id}', name: 'app_editStock_product', methods: ['GET', 'POST'])]
    public function editStockProduct(Request $request, ManagerRegistry $doctrine, int $id, ProductRepository $productRepo): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $product->setStock($product->getStock() + $request->get('modificationStock'));

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json($productRepo->find($product->getId()));
    }
}
