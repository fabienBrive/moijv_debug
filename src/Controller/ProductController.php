<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{

    /**
     * @Route("", name="product")
     * @Route("/{page}", name="product_paginated", requirements={"page"="\d+"})
     * @param ProductRepository $productRepo
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ProductRepository $productRepo, $page = 1)
    {
        $productList = $productRepo->findPaginatedByUser($this->getUser(), $page);

        return $this->render('product/index.html.twig', [
                'products' => $productList
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_product")
     * @param Product $product
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteProduct(Product $product, ObjectManager $manager)
    {
        if ($product->getOwner()->getId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException('You are not allowed to delete this product');
        }
        $manager->remove($product);
        $manager->flush();
        return $this->redirectToRoute('product');
    }

    /**
     * @Route("/add", name="add_product")
     * @Route("/edit/{id}", name="edit_product")
     * @param Request $request
     * @param ObjectManager $manager
     * @param Product|null $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editProduct(Request $request, ObjectManager $manager, Product $product = null)
    {
        if ($product === null) {
            $product = new Product();
            $group = 'insertion';
        } else {
            $oldImage = $product->getImage();
            $product->setImage(new File($product->getImage()));
            $group = 'edition';
        }

        $formProduct = $this->createForm(ProductType::class, $product, ['validation_groups' => [$group]])
            ->add('Envoyer', SubmitType::class, ['label' => 'form_product.label.submit']);

        $formProduct->handleRequest($request);

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            $product->setOwner($this->getUser());
            /** @var UploadedFile $image */
            $image = $product->getImage();
            if ($image === null) {
                $product->setImage($oldImage);
            } else {
                $newFileName = md5(uniqid()) . '.' . $image->guessExtension();

                $image->move('uploads', $newFileName);
                $product->setImage('uploads/' . $newFileName);
            }
            $manager->persist($product);
            $manager->flush();
            return $this->redirectToRoute('product');
        }

        return $this->render('product/edit_product.html.twig', [
                'form' => $formProduct->createView()
        ]);
    }

}
