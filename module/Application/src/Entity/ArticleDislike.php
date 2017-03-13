<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleDislike
 *
 * @ORM\Table(name="article_dislike", indexes={@ORM\Index(name="article_index", columns={"article"}), @ORM\Index(name="user_index", columns={"user"})})
 * @ORM\Entity
 */
class ArticleDislike
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="article", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $article;

    /**
     * @var integer
     *
     * @ORM\Column(name="user", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $user;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set article
     *
     * @param integer $article
     *
     * @return ArticleDislike
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return integer
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return ArticleDislike
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }
}

