<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Application\Service\FormServiceInterface;
use Zend\Form\FormInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Admin\Form\CategoryForm;
use Authentication\Service\ValidationServiceInterface;

class CategoryController extends AbstractActionController
{
    private $entityManager;
    private $categoryForm;
    private $formService;
    private $validationService;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategoryForm $categoryForm,
        FormServiceInterface $formService,
        ValidationServiceInterface $validationService
    ) {
        $this->entityManager     = $entityManager;
        $this->categoryForm      = $categoryForm;
        $this->formService       = $formService;
        $this->validationService = $validationService;
    }

    public function indexAction()
    {
        $categoriesQueryBuilder = $this->entityManager
                                       ->getRepository(Category::class)
                                       ->getCategoriesQueryBuilder();

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

            $repository = $this->entityManager->getRepository(Category::class);
            $name = trim(strip_tags($request->getPost('name')));

            if ($this->validationService->isObjectExists($repository, $name, ['name'])) {
                $nameExists = 'Category with name "' . $request->getPost('name') . '" exists already';
                $form->get('name')->setMessages(['nameExists' => $nameExists]);
                return [
                    'form' => $form,
                    'pageNumber' => $pageNumber,
                ];
            }

            $form->setData($request->getPost());

            if ($form->isValid()) {
                $category = $form->getData();

                /* In order do not select parent category (create new category)*/
                if ($category->getParentId() == 0) {
                    $category->setParentId(null);
                }

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

            $categoryNameOld = $category->getName();
            $categoryNameNew = trim(strip_tags($form->get('name')->getValue()));

            $repository = $this->entityManager->getRepository(Category::class);

            if ($repository->findBy(['name' => $categoryNameNew]) && $categoryNameNew !== $categoryNameOld) {
                $nameExists = 'Category with name "' . $categoryNameNew . '" exists already';
                $form->get('name')->setMessages(['nameExists' => $nameExists]);
            }

            if ($form->isValid() && empty($form->getMessages())) {
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

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        $this->flashMessenger()->setNamespace('success')->addMessage('Category deleted');

        return $this->redirect()->toRoute('admin/categories', ['page' => $pageNumber]);
    }
}
