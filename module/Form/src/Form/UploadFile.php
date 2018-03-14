<?php
namespace Form\Form;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter; 
use Zend\Filter; 
//use Zend\Validator\NotEmpty;
use Zend\Validator\File\UploadFile as FileUpload;
use Zend\Validator\File\FilesSize;
use Zend\Validator\File\MimeType;

class UploadFile extends Form{
    function __construct(){
        parent::__construct();
        $this->add([
            'type'=>'file',
            'name'=>'file-upload',
            'options'=>[
                'label'=>"Choose File:"
            ],
            // 'attributes'=>[
            //     'required'=>true
            // ]
        ]);
        $this->add([
            'type'=>'Submit',
            'name'=>'btnSubmit',
            'attributes'=>[
                'class'=>'btn btn-primary',
                'value'=>'Upload'
            ]
        ]);
        $this->filterUploadFile();
    }
    function filterUploadFile(){
        
        $fileUpload = new FileUpload();
        $fileUpload->setMessages([
            FileUpload::NO_FILE => 'Vui lòng chọn file'
        ]);

        $size = new FilesSize(['min'=>100,'max'=>1024]);

        $size->setMessages([
        	FilesSize::TOO_BIG=>"File bạn chọn quá lớn (%size%), yêu cầu < %max% kb",
        	FilesSize::TOO_BIG=>"File bạn chọn quá bé (%size%), yêu cầu > %min% kb"
        ]);

        $mimeType = new MimeType('');

        $mimeType->setMessages([
        	MimeType::FALSE_TYPE=>"MimeType không được phép, chỉ cho phép chọn file hình"
        ]);


        // File Input
        $fileInput = new \Zend\InputFilter\FileInput('file-upload');
        $fileInput->setRequired(true);
        $fileInput->getValidatorChain()
                    ->attach($fileUpload,true,2)
                    ->attach($size,true,1)
                    ->attach($mimeType,true,3);
        
        $filter = new InputFilter();
        
        $filter->add($fileInput);
        $this->setInputFilter($filter);
        /*
        $filter->add([
            'name'=>'file-upload',
            'required'=>true,
            'validators'=>[
                [
                    'name'=>'NotEmpty',
                    'options'=>[
                        'messages'=>[
                            NotEmpty::IS_EMPTY => "Vui lòng chọn file"
                        ]
                    ],
                    'break_chain_on_failure'=>true
                ]
            ]
            //check file size (<=1Mb), 
            //MimeType (image/png, image/jpeg), 
            //Rename file ;
            //Upload Multiple file
        ]);
        */
    }
}


// <!-- <?php
// namespace Form\Form;
// use Zend\Form\Element\Text;
// use Zend\Form\Form;
// use Zend\InputFilter\InputFilter; 
// use Zend\Validator\StringLength; 
// use Zend\Filter; 
// use Zend\Validator\NotEmpty; 

// class UploadFile extends Form{
// 	function __construct(){
// 		parent::__construct();

// 		$this->add([
// 			'type'=>'file',
// 			'name'=>'file-upload',
// 			'options'=>[
// 				'label'=>'Choose file:'
// 			],
// 			// 'attributes'=>[
// 			// 	'required'=>true
// 			// ]
// 		]);

// 		$this->add([
// 			'type'=>'submit',
// 			'name'=>'btnSubmit',
// 			'attributes'=>[
// 				'class'=>'btn btn-primary',
// 				'value'=>'Upload'
// 			]
// 		]);

// 		$this->filterUploadFile();
// 	}

// 	function filterUploadFile(){
// 		$filter = new InputFilter();
// 		$this->setInputFilter($filter);

// 		$filter->add([
// 			'name'=>'file-upload',
// 			'required'=>true,
// 			'validators'=>[
// 				[
// 					'name'=>'NotEmpty',
// 					'options'=>[
// 						'messages'=>[
// 							NotEmpty::IS_EMPTY=>'Vui lòng chọn file'
// 						]						
// 					]
// 				]
// 			]
// 		]);

// 		//check file-size, 
// 		//Mime Type (image/png, image/jpeg),
// 		//Rename file,
// 		//Upload Mutile file
// 	}
// } -->