<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;
use Application\Entity\Article;
use Application\Entity\Comment;
use Application\Entity\ArticleLike;
use Application\Entity\ArticleDislike;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Application\Service\FormServiceInterface;
use Zend\Form\FormInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Admin\Form\CategoryForm;

class CategoryController extends AbstractActionController
{
    private $entityManager;
    private $categoryForm;
    private $formService;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategoryForm $categoryForm,
        FormServiceInterface $formService
    ) {
        $this->entityManager = $entityManager;
        $this->categoryForm  = $categoryForm;
        $this->formService   = $formService;
    }

    public function indexAction()
    {
        $categoriesQueryBuilder = $this->entityManager
                                       ->getRepository(Category::class)
                                       ->getCategoriesQueryBuilder($this->entityManager);

        $adapter = new DoctrinePaginator(new ORMPaginator($categoriesQueryBuilder));
        $paginator = new Paginator($adapter);

        $currentPageNumber = (int)$this->params()->fromRoute('page', 1);
        $paginator->setCurrentPageNumber($currentPageNumber);

        $itemCountPerPage = 10;
        $paginator->setItemCountPerPage($itemCountPerPage);

        $pageNumber = (int)$paginator->getCurrentPageNumber();

        return new ViewModel([
            'categories' => $paginator,
            'pageNumber' => $pageNumber,
            'cnt' => 0,
            'categoriesCnt' => ($currentPageNumber - 1) * $itemCountPerPage,
        ]);
    }

    public function addAction()
    {
        $pageNumber = (int)$this->params()->fromRoute('page', 0);
        $category = new Category();

        /* For annotation form */
        $form = $this->formService->getAnnotationForm($this->entityManager, $category);
        $form->setValidationGroup(FormInterface::VALIDATE_ALL);

        /* For ordinary form */
        //$form = $this->categoryForm;
        //$form->setHydrator(new DoctrineObject($this->entityManager));
        //$form->bind($category);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $category = $form->getData();

                $this->flashMessenger()->setNamespace('success')->addMessage('Category added');

                $this->entityManager->persist($category);
                $this->entityManager->flush();

                return $this->redirect()->toRoute('admin/categories', ['page' => $pageNumber]);
            }
        }

        return new ViewModel([
            'pageNumber' => $pageNumber,
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        $pageNumber = (int)$this->params()->fromRoute('page', 0);
        $id         = (int)$this->params()->fromRoute('id', 0);

        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (! $pageNumber || ! $id || ! $category) {
            return $this->notFoundAction();
        }

        /* For annotation form */
        $form = $this->formService->getAnnotationForm($this->entityManager, $category);
        $form->setValidationGroup(FormInterface::VALIDATE_ALL);

        /* For ordinary form */
        //$form = $this->categoryForm;
        //$form->setHydrator(new DoctrineObject($this->entityManager));
        //$form->bind($category);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $category = $form->getData();

                $this->entityManager->persist($category);
                $this->entityManager->flush();

                $this->flashMessenger()->setNamespace('success')->addMessage('Category edited');

                return $this->redirect()->toRoute('admin/categories', ['page' => $pageNumber]);
            }
        }

        return [
            'pageNumber' => $pageNumber,
            'id'         => $id,
            'form'       => $form,
            'category'   => $category,
        ];
    }

    public function deleteAction()
    {
        $request    = $this->getRequest();
        $pageNumber = (int)$this->params()->fromRoute('page', 0);
        $id         = (int)$this->params()->fromRoute('id', 0);
        $category   = $this->entityManager->getRepository(Category::class)->find($id);

        if (! $request->isPost() || ! $pageNumber || ! $id || ! $category) {
            return $this->notFoundAction();
        }

        // Remove child categories
        $childCategories = $this->entityManager->getRepository(Category::class)->findBy(['parentId' => $category->getId()]);
        if ($childCategories) {
            foreach ($childCategories as $childCategory) {
                // Remove articles in category
                $articles = $this->entityManager->getRepository(Article::class)->findBy(['category' => $childCategory->getId()]);
                if ($articles) {
                    foreach ($articles as $article) {
                        // Remove comments in article
                        $this->deleteComments($article->getId());

                        // Remove likes in article
                        $this->deleteLikes($article->getId());

                        // Remove dislikes in article
                        $this->deleteDisLikes($article->getId());

                        $this->entityManager->remove($article);
                    }
                }
                $this->entityManager->remove($childCategory);
            }
        }

        // Remove articles in category
        $articles = $this->entityManager->getRepository(Article::class)->findBy(['category' => $id]);
        if ($articles) {
            foreach ($articles as $article) {
                // Remove comments in article
                $this->deleteComments($article->getId());

                // Remove likes in article
                $this->deleteLikes($article->getId());

                // Remove dislikes in article
                $this->deleteDisLikes($article->getId());

                $this->entityManager->remove($article);
            }
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        $this->flashMessenger()->setNamespace('success')->addMessage('Category deleted');

        return $this->redirect()->toRoute('admin/categories', ['page' => $pageNumber]);
    }

    private function deleteComments($articleId)
    {
        $comments = $this->entityManager->getRepository(Comment::class)->findBy(['article' => (int)$articleId]);
        if ($comments) {
            foreach ($comments as $comment) {
                $this->entityManager->remove($comment);
            }
        }
    }

    private function deleteLikes($articleId)
    {
        $articleLikes = $this->entityManager->getRepository(ArticleLike::class)->findBy(['article' => (int)$articleId]);
        if ($articleLikes) {
            foreach ($articleLikes as $articleLike) {
                $this->entityManager->remove($articleLike);
            }
        }
    }

    private function deleteDisLikes($articleId)
    {
        $articleDislikes = $this->entityManager->getRepository(ArticleDislike::class)->findBy(['article' => (int)$articleId]);
        if ($articleDislikes) {
            foreach ($articleDislikes as $articleDislike) {
                $this->entityManager->remove($articleDislike);
            }
        }
    }
}
