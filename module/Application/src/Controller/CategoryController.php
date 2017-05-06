<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;
use Application\Entity\Article;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class CategoryController extends AbstractActionController
{
    private $entityManager;
    const FILE_URL = './data/items-count-per-page/articles-count-per-category-page.txt';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $articlesCountPerPage = 10;

        if ($this->request->isPost('articles_count_per_category_page')) {
            $articlesCountPerCategoryPage = abs((int)$this->request->getPost('articles_count_per_category_page'));

            if (is_file(self::FILE_URL)) {
                file_put_contents(self::FILE_URL, $articlesCountPerCategoryPage);
            }

            return $this->redirect()->refresh();
        }

        $categoryId = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $category = $this->entityManager->getRepository(Category::class)->find($categoryId);

        $articlesQueryBuilder = $this->entityManager
                                     ->getRepository(Article::class)
                                     ->getArticlesQueryBuilderForCategory($categoryId, true);

        $adapter = new DoctrinePaginator(new ORMPaginator($articlesQueryBuilder));
        $paginator = new Paginator($adapter);

        $currentPageNumber = (int)$this->params()->fromRoute('page', 1);
        $paginator->setCurrentPageNumber($currentPageNumber);

        if (is_file(self::FILE_URL)) {
            $articlesCountPerPage = file_get_contents(self::FILE_URL);
        }
        $itemCountPerPage = $articlesCountPerPage;
        $paginator->setItemCountPerPage($itemCountPerPage);

        return new ViewModel([
            'category' => $category,
            'articles' => $paginator,
        ]);
    }
}
