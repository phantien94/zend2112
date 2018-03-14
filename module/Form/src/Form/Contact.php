<?php
namespace Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter; 
use Zend\Validator\StringLength; 
use Zend\Filter; 

class Contact extends Form{

	function __construct(){
		parent::__construct();
		
		$this->add([
			'type'=>'text',
			'name'=>'fullname',
			'options'=>[
				'label'=>'Fullname: ',
				'label_options'=>[
					'class'=>"control-label"
				]
			],
			'attributes'=>[
				'class'=>'form-control',
				'placeholder'=>"Enter fullname"
			]
		]);

		$this->add([
			'type'=>'Email',
			'name'=>'email',
			'options'=>[
				'label'=>'Email: ',
				'label_options'=>[
					'class'=>"control-label"
				]
			],
			'attributes'=>[
				'class'=>'form-control',
				'placeholder'=>"Enter your email"
			]
		]);

		$this->add([
			'type'=>'text',
			'name'=>'subject',
			'options'=>[				
				'label'=>'Title: ',
				'label_options'=>[
					'class'=>"control-label"
				]
			],
			'attributes'=>[
				'class'=>'form-control',
				'placeholder'=>"Enter subject"
			]
		]);

		$this->add([
			'type'=>'Textarea',
			'name'=>'message',
			'options'=>[
				'class'=>'form-control',
				'label'=>'Your message: ',
				'label_options'=>[
					'class'=>"control-label"
				]
			],
			'attributes'=>[
				'class'=>'form-control',
				'placeholder'=>"Enter message"
			]
		]);

		$this->add([
			'type'=>'submit',
			'name'=>'btnSubmit',
			'attributes'=>[
				'class'=>'btn btn-primary',
				'value'=>'Send'
			]
		]);

		$this->filterForm();
	}
	
	function filterForm(){
		$filter = new InputFilter();
		$this->setInputFilter($filter);

		//fullname: required, min:5
		$filter->add([
			'name'=>'fullname',
			'required'=>true,
			'filters'=>[
				['name'=>Filter\StripNewlines::class],
				['name'=>Filter\StringToUpper::class]
			],
			'validators'=>[
				[
					'name'=>'StringLength', //hoặc ghi StringLenght::class
					'options'=>[
						'min'=>5,
						'messages'=>[
							StringLength::TOO_SHORT=>"Họ tên ít nhất %min% ký tự"
						]
					]
				]
			]
		]);

		//email: required, min: 10, max: 50, valid

		$filter->add([
			'name'=>'email',
			'required'=>true,
			'validators'=>[
				[
					'name'=>'StringLength',
					'options'=>[
						'min'=>10,
						'max'=>50,
						'messages'=>[
							StringLength::TOO_SHORT=>"Email ít nhất %min% ký tự",
							StringLength::TOO_LONG=>"Email tối đa %max% ký tự"
							
						]
					]
				]
			]
		]);

		//title: required, max: 100

		$filter->add([
			'name'=>'subject',
			'required'=>true,
			'validators'=>[
				[
					'name'=>'StringLength',
					'options'=>[
						'max'=>100,
						'messages'=>[
							StringLength::TOO_SHORT=>"Tiêu đề tối đa %max% ký tự",
						]
					]
				]
			]
		]);

		//message: required

		$filter->add([
			'name'=>'message',
			'required'=>true
		]);
	}
}
?>