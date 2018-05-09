<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use User\Entity\User;
use Zend\View\Model\ViewModel;
use User\Form\UserForm;
use User\Form\ResetPasswordForm;
use User\Form\ForgetPasswordForm;
use Zend\View\Model\JsonModel;


class UserController extends AbstractActionController{

    private $entityManager;
    private $userManager ;

    function __construct($entityManager, $userManager ){
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }
    
    function indexAction(){
    
        //$users = $this->entityManager->find(User::class, 10);
        //$users = $this->entityManager->getRepository(User::class)->find(10);

        //$users = $this->entityManager->getRepository(User::class)->findBy([]);
        $users = $this->entityManager->getRepository(User::class)->findAll();

        //$users = $this->entityManager->getRepository(User::class)->findOneByFullname('huong01@gmail.com');
        //print_r($users);
        
        return new ViewModel(['users'=>$users]);
    }

    function addAction(){
        $form = new UserForm('add');

        $request = $this->getRequest();
        if($request->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()){
                $data = $form->getData();
                
                // echo "<pre>";
                // print_r($data);
                // echo "</pre>";
                // return false;

                // validate pw vs confirm pw
                if($data['confirm_password']!==$data['password']){
                    $this->flashMessenger()->addErrorMessage('Mật khẩu không giống nhau');
                    return $this->redirect()->toRoute('user',[
                        'controller'=>'user',
                        'action'=>'add'
                    ]);  
                }

                //check Email Exist
                $user = $this->userManager->checkEmailExists($data['email']);

                //var_dump($user); return false;
                if(!$user){
                    $this->flashMessenger()->addErrorMessage('Email đã có người sử dụng');
                    return $this->redirect()->toRoute('user',[
                        'controller'=>'user',
                        'action'=>'add'
                    ]);  
                }

                $user = $this->userManager->insertUser($data);
                $this->flashMessenger()->addSuccessMessage('Đăng kí thành công');
                return $this->redirect()->toRoute('user',[
                    'controller'=>'user',
                    'action'=>'index'
                ]);

            }
        }
        return new ViewModel(['form'=>$form]);
    }

    function editAction(){
        $id = $this->params()->fromRoute('id',0);
        $user = $this->userManager->findUserByid($id);
        
        if($id==0 || !$user){
            $this->flashMessenger()->addWarningMessage('Không tìm thấy user');
            return $this->redirect()->toRoute('user',[
                'controller'=>'user',
                'action'=>'index'
            ]);
        }
        $form = new UserForm('edit');

        $request = $this->getRequest();
        if($request->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()){
                $data = $form->getData();
                $check = $this->userManager->checkEmailExists($data['email'],$id);

                // print_r($user);
                // return false;
                if($check){
                    $user = $this->userManager->updateUser($user, $data);
                    $this->flashMessenger()->addSuccessMessage('Cập nhật thành công');
                    return $this->redirect()->toRoute('user',[
                        'controller'=>'user',
                        'action'=>'index'
                    ]);
                }
                $this->flashMessenger()->addErrorMessage('Email đã có người sử dụng');
                return $this->redirect()->toRoute('user',[
                    'controller'=>'user',
                    'action'=>'edit',
                    'id'=>$id
                ]);  
            }
            
        }

        $data = [
            'fullname'=>$user->getFullname(),
            'email' => $user->getEmail(),
            'birthdate' => $user->getBirthdate(),
            'gender'=>$user->getGender(),
            'address' => $user->getAddress(),
            'phone' => $user->getPhone()
        ];
        $form->setData($data);

        return new ViewModel(['form'=>$form,'user'=>$user]);
    }
    function deleteAction(){
        $id = $this->params()->fromRoute('id');
        $user = $this->userManager->findUserByid($id);
        if($user == false){
            $result = 'error';
        }
        else{
            $this->userManager->removeUser($user);
            $result = "success";
        }
        return new JsonModel(['result'=>$result]);
    }

    function changePasswordAction(){
        $id = $this->params()->fromRoute('id',0);
        $user = $this->userManager->findUserByid($id);
        
        if($id==0 || !$user){
            $this->flashMessenger()->addWarningMessage('Không tìm thấy user');
            return $this->redirect()->toRoute('user',[
                'controller'=>'user',
                'action'=>'index'
            ]);
        }
        $form = new ResetPasswordForm;

        $request = $this->getRequest();
        if($request->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()){
                $data = $form->getData();

                // validate pw vs confirm pw
                if($data['confirm_password']!==$data['password']){
                    $this->flashMessenger()->addErrorMessage('Mật khẩu không giống nhau');
                    return $this->redirect()->toRoute('user',[
                        'controller'=>'user',
                        'action'=>'changePassword',
                        'id'=>$id
                    ]);  
                }
                $securePass = $user->getPassword();
                $oldPassword = $data['old_password'];

                if($this->userManager->verifyPassword($oldPassword,$securePass)){
                    // Lưu pass mới
                    $user = $this->userManager->changePassword($user, $data['password']);
                    $this->flashMessenger()->addSuccessMessage('Mật khẩu đã thay đổi');
                    return $this->redirect()->toRoute('user',[
                        'controller'=>'user',
                        'action'=>'index'
                    ]);  
                }

                else{
                    $this->flashMessenger()->addErrorMessage('Mật khẩu cũ chưa chính xác');
                    return $this->redirect()->toRoute('user',[
                        'controller'=>'user',
                        'action'=>'changePassword',
                        'id'=>$id
                    ]);  
                }
            }
            
        }
        return new ViewModel(['form'=>$form,'user'=>$user]);
    }

    function forgetPasswordAction(){
        $form = new ForgetPasswordForm();

        $request = $this->getRequest();
        if($request->isPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()){
                //check email exits
                if(!$this->userManager->checkEmailExists($data['email'])){
                    //update token 
                    $user = $this->userManager->findUserByEmail($data['email']);
                    $user = $this->userManager->updateToken($user);
                    $this->userManager->sendMailWithToken($user);
                    $this->flashMessenger()->addSuccessMessage('Vui lòng kiểm tra hộp thư để đặt mật khẩu mới');
                }
                else $this->flashMessenger()->addErrorMessage('Không tìm thấy email');

                return $this->redirect()->toRoute('forget-password',[
                    'controller'=>'user',
                    'action'=>'forgetPassword'
                ]);
            
            }
        }
        return new ViewModel([
            'form'=>$form
        ]);
    }

    function setPasswordAction(){
        $token = $this->params()->fromRoute('token',null);
        
        $form = new ResetPasswordForm('forget-pw');

        $request = $this->getRequest();
        if($token == null || $request->isGet()){
            
            if($token !== null && !$this->userManager->checkResetPasswordToken($token)){
                $this->flashMessenger()->addErrorMessage('Đường dẫn bạn nhập vào không hợp lệ hoặc đã hết hạn sử dụng. Vui lòng kiểm tra lại');
    
                return $this->redirect()->toRoute('forget-password',[
                    'controller'=>'user',
                    'action'=>'forgetPassword'
                ]);
            }
        }

        if($request->isPost()){
            //echo $token; return false;
            $data = $this->params()->fromPost();
            $token = $data['token']; //dãy token hidden trong form

            $form->setData($data);
            if($form->isValid()){
                $data = $form->getData();

                // validate pw vs confirm pw
                if($data['confirm_password']!==$data['password']){
                    $this->flashMessenger()->addErrorMessage('Mật khẩu không giống nhau');
                    return $this->redirect()->toRoute('user',[
                        'controller'=>'user',
                        'action'=>'setPassword',
                        'token'=>$token
                    ]);  
                }
                $user = $this->userManager->findUserByToken($token);
            
                $this->userManager->changePassword($user, $data['password']);

                //Sau khi đổi pass mới, xóa token trong database
                $this->userManager->deleteToken($user);

                $this->flashMessenger()->addSuccessMessage('Mật khẩu đã thay đổi');
                return $this->redirect()->toRoute('user',[
                    'controller'=>'user',
                    'action'=>'index'
                ]);  
            }
            
        }

        $view = new ViewModel(['form'=>$form,'token'=>$token]);
        $view->setTemplate('user/user/reset-password');
        return $view;
    } 

}
?>