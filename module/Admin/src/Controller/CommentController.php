<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Article;
use Application\Entity\Comment;

class CommentController extends AbstractActionController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $comments = null;
        $articleId = (int)$this->getEvent()->getRouteMatch()->getParam('article');
        if ($articleId) {
            $comments = $this->entityManager->getRepository(Comment::class)->findBy(['article' => $articleId]);
        }

        return new ViewModel([
            'comments' => $comments,
        ]);
    }

    public function searchAction()
    {
        $articles = '';
        $request = $this->getRequest();
        $response = $this->getResponse();

        if (! $request->isPost()) {
            return $this->notFoundAction();
        }

        $search = $request->getPost('search-article');

        $stringTrim = new \Zend\Filter\StringTrim();
        $stripTags= new \Zend\Filter\StripTags();

        $search = $stringTrim->filter($search);
        $search = $stripTags->filter($search);

        if (! empty($search)) {
            $articles = $this->entityManager->getRepository(Article::class)->searchArticle($search);
        }

        $response->setContent(\Zend\Json\Json::encode($articles));
        return $response;
    }

    public function deleteAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        $articleId = (int)$this->getEvent()->getRouteMatch()->getParam('article');
        $comment = $this->entityManager->getRepository(Comment::class)->find($id);
        $request = $this->getRequest();

        if (! $id || ! $comment || ! $request->isPost()) {
            return $this->notFoundAction();
        }

        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        $this->flashMessenger()->setNamespace('success')->addMessage('Comment deleted');

        return $this->redirect()->toRoute('admin/comments', ['article' => $articleId]);
    }
}
