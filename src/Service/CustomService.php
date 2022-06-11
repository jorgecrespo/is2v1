<?php

namespace App\Service;

use App\Entity\Pacientes;
use App\Entity\Usuarios;
use DateTime;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class CustomService
{

    // private $devMode = true;
    private $devMode = !false;



    
    
    
    /*
    Tener en cuenta:
     Fiebre amarilla debe tener id 3

     Los estados del turno son : ASIGNADO, APLICADA, 
    */

    private $session;

    private $paginasPaciente = [
        '/homepaciente',
        '/enconstruccion',
        '/completar/vacunacion',
        '/token',
        '/asignacion/turno',
    ];

    private $paginasVacunador = [
        '/enconstruccion',
        '/home/vacunador',
    ];


    private $paginasAdmin = [
        '/altavacunador',
        // '/altapaciente',
        '/vacunadoresporcentro',
        '/modificarvacunador',
        '/enconstruccion',
        '/homeadmin',
        '/',
        '/dev/pacientes', // Quitar en prod
        '/dev/vacunadores',// Quitar en prod
        '/modificar/vacunatorio'
    ];

    private $paginasPublicas = [
        '/enconstruccion',
        '/altapaciente',
        // '/altavacunador',
        '/custom/login/paciente',
        '/login/admin',
        '/dev/pacientes', // Quitar en prod
        '/dev/vacunadores',// Quitar en prod

    ];

    private $paginasHome = [
        'P' => '/homepaciente',
        'V' => '/home/vacunador',
        'A' => '/homeadmin',
        'x' => '/custom/login/paciente'
    ];


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

    public function getEdad(DateTime $fecha){
        $now = new DateTime();
        $interval = $now->diff($fecha);
        return $interval->y;
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


    public function isDevMode(){
        return $this->devMode;
    }

    public function validarUrl($url):bool
    {
        if (!$this->isDevMode()){
            switch ($this->getUser()['rol']){
                case 'P':
                    return in_array($url, $this->paginasPaciente);
                    break;
                case 'V':
                    return in_array($url, $this->paginasVacunador);
                    break;
                case 'A':
                    return in_array($url, $this->paginasAdmin);
                    break;
                case 'x':
                    return in_array($url, $this->paginasPublicas);
                    break;

            }
        } else{
            return true; //si estoy en devMode
        }


        // return ($this->getUser()['rol'] == 'P' && !$this->isDevMode()) ;
     
        // retornar a pagina principal segun usuario
        // home de usuario o alguna pagina del vacunador
        // preguntar devMode
    }

    public function getHomePageByUser(): string
    {
        return $this->paginasHome[$this->getUser()['rol']];
    }

   

    public function generarToken($userName):string {

        if (strlen($userName)< 4){
            $userName  = str_pad($userName, 4);
        }

        $str4 = str_split(substr(strtolower($userName), 0,4));

        $token= '';
        foreach ($str4 as $letra){
            switch ($letra){
                case 'a':
                case 'b':
                case 'c':
                case 'd':
                    $letra = '4';
                    break;
                    case 'e':
                    case 'f':
                    case 'g':
                    case 'h':
                    $letra = '3';
                    break;
                case 'i':
                case 'j':
                case 'k':
                case 'l':
                case 'm':
                case 'n':
                    $letra = '1';
                    break;
                case 'o':
                case 'p':
                case 'q':
                case 'r':
                case 's':
                case 't':
                    $letra = '0';
                    break;
                case 'u':
                case 'v':
                case 'w':
                case 'x':
                case 'y':
                case 'z':
                    $letra = '9';
                    break;
                case ' ':
                    $letra = '8';
                    break;
            }
            $token = $letra . $token;

        }
        $token = strtoupper($token);
        return $token;
    }


  



}