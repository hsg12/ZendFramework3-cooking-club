<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\User;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;

class UserController extends AbstractActionController
{
    private $entityManager;
    const FILE_URL = './data/items-count-per-page/users-count-per-admin-page.txt';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $usersCountPerPage = 10;

        if ($this->request->isPost('users-count-per-admin-page')) {
            $usersCountPerAdminPage = abs((int)$this->request->getPost('users-count-per-admin-page'));

            if (is_file(self::FILE_URL)) {
                file_put_contents(self::FILE_URL, $usersCountPerAdminPage);
            }

            return $this->redirect()->refresh();
        }

        $admins = $this->entityManager->getRepository(User::class)->getAdmins();
        $usersQueryBuilder = $this->entityManager->getRepository(User::class)->getUsersQueryBuilder();

        $adapter = new DoctrinePaginator(new ORMPaginator($usersQueryBuilder));
        $paginator = new Paginator($adapter);

        $currentPageNumber = (int)$this->params()->fromQuery('page', 1);
        $paginator->setCurrentPageNumber($currentPageNumber);

        if (is_file(self::FILE_URL)) {
            $usersCountPerPage = file_get_contents(self::FILE_URL);
        }
        $itemCountPerPage = $usersCountPerPage;
        $paginator->setItemCountPerPage($itemCountPerPage);

        $pageNumber = (int)$paginator->getCurrentPageNumber();

        return new ViewModel([
            'admins' => $admins,
            'paginator' => $paginator,
            'pageNumber' => $pageNumber, //to go back to the same page
            'cnt' => 0,
            'usersCnt' => ($currentPageNumber - 1) * $itemCountPerPage,
        ]);
    }

    public function editAction()
    {
        $page = (int)$this->params()->fromRoute('page', 0);
        $id = (int)$this->params()->fromRoute('id');
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if ((! $id) || (! $user)) {
            return $this->notFoundAction();
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $role = $request->getPost('role');
            $user->setRole($role);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->flashMessenger()->setNamespace('success')->addMessage('Value was edited');

            return $this->redirect()->toRoute('admin/users', [], ['query' => ['page' => $page]]);
        }

        return new ViewModel([
            'user' => $user,
            'page' => $page,
        ]);
    }

    public function searchAction()
    {
        $response = $this->getResponse();
        $request = $this->getRequest();
        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        $result = '';
        $page   = abs((int)$request->getPost('pageNumber'));  //to go back to the same page
        $search = trim(strip_tags($request->getPost('formData')));

        if (! empty($search)) {
            $result = $this->entityManager->getRepository(User::class)->searchUser($search);
        }

        $response->setContent(\Zend\Json\Json::encode([$result, $page]));
        return $response;
    }

    public function deleteAction()
    {
        $page = (int)$this->params()->fromRoute('page', 0);
        $request = $this->getRequest();
        $id = (int)$this->params()->fromRoute('id');
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if ((! $request->isPost()) || (! $id) || (! $user)) {
            return $this->notFoundAction();
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirect()->toRoute('admin/users', [], ['query' => ['page' => $page]]);
    }
}
