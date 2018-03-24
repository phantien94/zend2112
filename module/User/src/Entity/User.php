<?php
namespace User\Entity;

use Doctrine\ORM\Mapping\ClassMetadata as Mapping;

/**
 * @Mapping\Mapping\Entity
 * @Mapping\Mapping\Table(name="users")
 */
class User{

    /**
     * @Mapping\Mapping\Id
     * @Mapping\Mapping\Column(type="integer")
     * @Mapping\Mapping\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** @Mapping\Column(type="string",unique="true") */
    protected $email;

    /** @Mapping\Column(type="string") */
    protected $password;

    /** @Mapping\Column(type="string") */
    protected $fullname;

    /** @Mapping\Column(type="date") */
    protected $birthdate;

    /** @Mapping\Column(type="string") */
    protected $gender;

    /** @Mapping\Column(type="string") */
    protected $address;

    /** @Mapping\Column(type="string") */
    protected $phone;

    /** @Mapping\Column(type="boolean") */
    protected $role;

    /** @Mapping\Column(type="string") */
    protected $token;

    /** @Mapping\Column(type="datetime",name="created_at") */
    protected $created_at;
    
    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setPassword($pw){
        $this->pw = $pw;
    }

    public function getPassword(){
        return $this->pw;
    }

    public function setFullname($fullname){
        $this->fullname = $fullname;
    }

    public function getFullname(){
        return $this->fullname;
    }

    public function setBirthdate($birthdate){
        $this->birthdate = $birthdate;
    }

    public function getBirthdate(){
        return $this->birthdate;
    }

    public function setGender($gender){
        $this->gender = $gender;
    }

    public function getGender(){
        return $this->gender;
    }

    public function setAddress($address){
        $this->address = $address;
    }

    public function getAddress(){
        return $this->address;
    }

    public function setPhone($phone){
        $this->phone = $phone;
    }

    public function getPhone(){
        return $this->phone;
    }

    public function setRole($role){
        $this->role = $role;
    }

    public function getRole(){
        return $this->role;
    }

    public function setToken($token){
        $this->token = $token;
    }

    public function getToken(){
        return $this->token;
    }

    public function setCreated_at($created_at){
        $this->created_at = $created_at;
    }

    public function getCreated_at(){
        return $this->created_at;
    }
}

?>