<?php

namespace App\Service;

use App\Entity\Pacientes;
use App\Entity\Usuarios;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class CustomService
{
    private $session;

    public function __construct(RequestStack $requestStack){

        $this->session = $requestStack->getSession();
    }

    public function getUser():array
    {

        $userId = $this->session->get('user','x');
        $userRol = $this->session->get('rol','x');
        $user = array('user' => $userId, 'rol' => $userRol);


        return $user;
    }

    public function setUser(String $user, String $rol)
    {

        $this->session->set('user',$user);
        $this->session->set('rol',$rol);

    }

    public function deleteUser()
    {

        $this->session->set('user','x');
        $this->session->set('rol', 'x');

        return;
    }

    public function logout(){
        $this->deleteUser();
        return new RedirectResponse('/');
    }

    public function hashPass(String $plainPass, String $tipoUsuario): string
    {
    
        password_hash($plainPass,PASSWORD_BCRYPT);


        $passwordHasherFactory = new PasswordHasherFactory([
            PasswordAuthenticatedUserInterface::class => ['algorithm' => 'auto'],
        ]);
        $passwordHasher = new UserPasswordHasher($passwordHasherFactory);
         
        if ($tipoUsuario == 'V' )
        $user = new Usuarios();
        else
        $user = new Pacientes();

        $plaintextPassword = $plainPass;
         
        $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
         
        return $hashedPassword;
    }



}