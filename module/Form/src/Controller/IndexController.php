<?php
namespace Form\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Form\Form\FormElement;
use Zend\Validator\StringLength;
use Zend\Validator\InArray;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;
use Zend\Validator\Regex;

class IndexController extends AbstractActionController{
    
    function indexAction(){
        $request = $this->getRequest();
        if($request->isPost()){
            $data = $this->params()->fromPost();
            $file = $request->getFiles()->toArray();
            echo "<pre>";
            print_r($data);
            print_r($file);
            echo "</pre>";
        }
        $form = new FormElement;
        $view = new ViewModel(['forms'=>$form]);
        $view->setTemplate('form/element/index');
        return $view;
    }

    function validatorAction(){

    	$request = $this->getRequest();
    	if($request->isPost()){
    	 	$dataInput = $this->params()->fromPost();
    	 	$fullname = $dataInput['fullname'];
            $subject = $dataInput['subject'];

    	 	// print_r($fullname);
    	 	$validatorFullname = new StringLength([
    	 		'min'=>5,
    	 		'max'=>20
    	 	]);

            if($validatorFullname->isValid($fullname)){
                //valid
                echo "Valid fullname";
                echo "<br>";
                echo $fullname;
                echo "<br>";
                echo $length = $validatorFullname->getStrLength();
                echo "<br>";
            }
            else{
                $messages = $validatorFullname->getMessages();
                foreach ($messages as $err) {
                    echo $err."<br>";
                }
            }

            $validatorSubject = new InArray([
                'haystack' => ['php','js','html']
            ]);

            foreach($subject as $monhoc){
                if($validatorSubject->isValid($monhoc)){
                //valid
                echo "<hr>";
                echo "Valid subject";
                echo "<br>";
                echo $monhoc;
                echo "</br>";
             }  
            else
            {
                $messages = $validatorSubject->getMessages();
                foreach ($messages as $err) {
                    echo $err."<br>";
                }
            }
           
            }
            

    	$form = new FormElement;
        $view = new ViewModel(['forms'=>$form]);
        $view->setTemplate('form/element/validator');
        return $view;
        }
    }
     function validator02Action(){
            // $validator = new\Zend\Validator\EmailAddress();
            // $email = "tien@gmail.com";
            
            // $validator = new NotEmpty();
            // $validator = new NotEmpty(NotEmpty::INTEGER);
            // $data = 1;

            // $validator = new\Zend\Validator\InArray([
            //     'haystack'=>[
            //         ['value1', 'value2', 222, 1, false],
            //         [1,2,3]
            //     ],
            //     'recursive'=>true, //true: Cho phép đệ quy
            //     'strict'=>false //false: cho phép data type
            // ]);
            // //$data = ['value1', 'value2', 222, 0, false]; true
            // $data = 0;

            // $validator = new\Zend\Validator\File\Count([
            //     'min'=>2,
            //     'max'=>4
            // ]);

            // $data=[
            //     __DIR__.'/img/images/test.txt',
            //      __DIR__.'/img/images/logo.png'

            // ];

        $validator = new \Zend\Validator\File\Exists();
        $data = FILE_PATH.'images/a.jpg';
            if($validator->isValid($data)){
                echo "Tồn tại file";
                echo "<hr>";
                //cho phep file png, jpg
                $extCheck = new \Zend\Validator\File\Extension([
                    'extension'=>'PNG, jpg',
                    'case'=>false
            ]);
            $extCheck->setMessages([
                $extCheck::FALSE_EXTENSION => "File không được phép chọn",
                \Zend\Validator\File\Extension::NOT_FOUND => "Không tìm thấy extension"
            ]);
                if($extCheck->isValid($data)){
                    echo "File được phép chọn";
                    echo "<hr>";
                    
                    // size
                    $sizeCheck = new \Zend\Validator\File\Size([
                        'min'=>1024,
                        'max'=>10240 //10kb
                    ]);

                    if($sizeCheck->isValid($data)){
                        echo "File size thoả mãn. Được phép upload";
                    }
                    else{
                        foreach($sizeCheck->getMessages() as $err){
                            echo $err;
                            echo "<br>";
                        }
                    }
                echo "<hr>";

                //kích thước 
                $imgSize = new \Zend\Validator\File\ImageSize([
                    'minwidth' => 140,
                    'maxwidth' => 150,
                    'minheight' => 50,
                    'maxheight' => 100,
                ]);
                $imgSize->setMessages([
                    $imgSize::WIDTH_TOO_BIG=>'Chiều rộng %width% vượt quá giới hạn cho phép là %maxwidth%',
                    $imgSize::HEIGHT_TOO_BIG=>'Chiều cao %height% vượt quá giới hạn cho phép là %maxheight%'
                ]);
                if($imgSize->isValid($data)){          
                    echo "kích thước file thoả mãn. Được phép upload";
                }
                else{
                    
                    foreach($imgSize->getMessages() as $err){
                        echo $err;
                        echo "<br>";
                    }
                }
            }
            else{
                foreach($extCheck->getMessages() as $err){
                    echo $err;
                    echo "<br>";
                }
            }
        }
        else{
            foreach($validator->getMessages() as $err){
                echo $err;
                echo "<br>";
            }
        }
        return false;
        }

    function validator03Action(){
        //pw: min: 6, phải chứa các kt đặc biệt @#$%^!*
        $validatorChain = new ValidatorChain();
        $validatorChain->attach(new StringLength(['min'=>6]));
        $validatorChain->attach(new Regex(
            ['pattern'=>'/[@#$%^!*]+/']
        ));
        $password = "aadsaa@";
        if($validatorChain->isValid($password)){
            echo "Pass thỏa mãn";
        }
        else{
            foreach ($validatorChain->getMessages() as $err) {
                        echo $err;
                        echo "<br>";
                    }  
        }
        return false;
    }

    function validator04Action(){
        //email
        //Đúng định dạng email, ít nhất 10 ký tự, max 50 ký tự, ko rỗng

        $emailAddress = new EmailAddress();
        $emailAddress->setMessages([
            EmailAddress::INVALID_FORMAT => "Email không đúng định dạng",
            EmailAddress::INVALID => "Không đúng kiểu dữ liệu email",
            EmailAddress::INVALID_HOSTNAME => "%hostname% không tồn tại"
        ]);

        $stringLength = new StringLength(['min'=>10,'max'=>50]);
        $stringLength->setMessages([
            StringLength::TOO_LONG => "Email không quá 50 ký tự",
            StringLength::TOO_SHORT =>"Email tối thiểu %min% ký tự"
        ]);

        $notEmpty = new NotEmpty();
        $notEmpty->setMessages([
            NotEmpty::IS_EMPTY => "Vui lòng nhập email"
        ]);

        $validatorChain = new ValidatorChain();
        $validatorChain->attach($notEmpty,true,3); 
        $validatorChain->attach($emailAddress,true,1);
        $validatorChain->attach($stringLength,false,2);
       

        //$email = "huong@gmail.com"; //true
        // $email = ""; //false
        $email = "huong";

        if($validatorChain->isValid($email)){
            echo "Email thỏa mãn";
        }
        else{
            foreach ($validatorChain->getMessages() as $err) {
                        echo $err;
                        echo "<br>";
            }  
        }
        return false;

    }

}
?>