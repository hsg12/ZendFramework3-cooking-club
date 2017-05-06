<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
 * Gallery
 *
 * @ORM\Table(name="gallery", indexes={@ORM\Index(name="gallery_user_id_key", columns={"user_id"})})
 * @ORM\Entity
 *
 * @Annotation\Name("gallery")
 */
class Gallery
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
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\File")
     * @Annotation\Name("file")
     * @Annotation\Attributes({"id":"file", "class":"jfilestyle", "required":"required"})
     * @Annotation\Required({"required":"true"})
     * @Annotation\Validator({"name":"Zend\Validator\File\Extension", "options":{
     *     "extension":{"png", "jpg", "jpeg", "gif"}
     * }})
     * @Annotation\Validator({"name":"Zend\Validator\File\IsImage"})
     * @Annotation\Validator({"name":"Zend\Validator\File\MimeType", "options":{
     *     "mimeTypes":{"image/gif", "image/jpg", "image/png", "image/jpeg"}
     * }})
     * @Annotation\Validator({"name":"Zend\Validator\File\FilesSize", "options":{
     *     "min":"64",
     *     "max":"100",
     * }})
     * @Annotation\Input("Zend\InputFilter\FileInput")
     * @Annotation\Filter({
     *     "name":"FileRenameUpload",
     *     "options":{
     *         "target":"./public_html/img/gallery/",
     *         "useUploadName":true,
     *         "useUploadExtension":true,
     *         "overwrite":true,
     *         "randomize":false
     *     }
     * })
     */
    private $image;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $user;

    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"class":"btn btn-default btn-sm add_to_gallery", "value":"Add image"})
     * @Annotation\AllowEmpty({"allowempty":"true"})
     */
    private $submit;

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
     * Set image
     *
     * @param string $image
     *
     * @return Gallery
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set user
     *
     * @param \Application\Entity\User $user
     *
     * @return Gallery
     */
    public function setUser(\Application\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}

