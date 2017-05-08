/**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100, precision=0, scale=0, nullable=true, unique=false)
     *
     * @Annotation\Type("Zend\Form\Element\File")
     * @Annotation\Name("file")
     * @Annotation\Attributes({"class":"jfilestyle", "id":"file"})
     * @Annotation\Options({"label":"Upload image"})
     * @Annotation\Validator({"name":"Zend\Validator\File\Extension", "options":{
     *     "extension":{"png", "jpeg", "jpg", "gif"},
     *     "breakChainOnFailure":true
     * }})
     * @Annotation\Validator({"name":"Zend\Validator\File\IsImage", "options":{"breakChainOnFailure":true}})
     * @Annotation\Validator({"name":"Zend\Validator\File\Size", "options":{"max":"20000000"}})
     * @Annotation\Validator({"name":"Zend\Validator\File\MimeType", "options":{"mimeType":"image"}})
     * @Annotation\Input("Zend\InputFilter\FileInput")
     * @Annotation\Filter({
     *     "name":"FileRenameUpload",
     *     "options":{
     *         "target":"./public_html/img/article/",
     *         "useUploadName":true,
     *         "useUploadExtension":true,
     *         "overwrite":true,
     *         "randomize":false
     *     }
     * })
     */
    private $image = '/img/article/no-image.jpg';
	
	
	
	
	
	////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	
	
	
	$this->add([
            'type'     => FileInput::class,
            'name'     => 'file',
            'required' => false,
            'validators' => [
                ['name'    => 'FileUploadFile'],
                ['name'    => 'FileIsImage'],
                [
                    'name' => 'Zend\Validator\File\Extension',
                    'options' => [
                        'extension' => ['png', 'jpg', 'jpeg', 'gif'],
                    ],
                ],
                [
                    'name' => 'Zend\Validator\File\Size',
                    'options' => [
                        'max' => 20000000,
                    ],
                ],
            ],
            'filters'  => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target'            =>'./public_html/img/article/',
                        'useUploadName'     =>true,
                        'useUploadExtension'=>true,
                        'overwrite'         =>true,
                        'randomize'         =>false
                    ]
                ]
            ],
        ]);