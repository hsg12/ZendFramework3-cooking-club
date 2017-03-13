<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Article;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class IndexController extends AbstractActionController
{
    private $entityManager;
    const FILE_URL = './data/items-count-per-page/articles-count-per-main-page.txt';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $articlesCountPerPage = 10;

        if ($this->request->isPost('articles_count_per_main_page')) {
            $articlesCountPerMainPage = abs((int)$this->request->getPost('articles_count_per_main_page'));

            if (is_file(self::FILE_URL)) {
                file_put_contents(self::FILE_URL, $articlesCountPerMainPage);
            }

            return $this->redirect()->refresh();
        }

        $articlesQueryBuilder = $this->entityManager
                                     ->getRepository(Article::class)
                                     ->getArticlesQueryBuilder($this->entityManager, true);

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
            'articles' => $paginator,
        ]);
    }
}
