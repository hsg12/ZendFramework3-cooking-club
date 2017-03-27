<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;

class GetCategory extends AbstractHelper
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke()
    {
        $result = $this->entityManager->getRepository(Category::class)->findAllCategories($this->entityManager);

        if (! $result) {
            return false;
        }

        foreach ($result as $value) {
            $categories[$value['parentId']][] = $value;
        }

        $tree = $this->buildTree($categories, null);
        return $tree;
    }

    public function buildTree($categories, $id)
    {
        $output = '';

        if (is_array($categories) && isset($categories[$id])) {
            $output .= '<ul class="menu_vert">';
                foreach ($categories[$id] as $category) {
                    $output .= '<li><a href="/category/' . $category['id'] . '">' . $category['name'] . '</a>';
                    $output .= $this->buildTree($categories, $category['id']);
                    $output .= '</li>';
                }
            $output .= '</ul>';
        }

        return $output;
    }
}
