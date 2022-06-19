<?php

namespace App\Service;
date_default_timezone_set('America/Buenos_Aires');

use App\Entity\Notificaciones;
use App\Entity\Pacientes;
use App\Entity\Turnos;
use App\Entity\Usuarios;
use App\Entity\Vacunas;
use App\Entity\Vacunatorios;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;


class CustomService
{

    // private $devMode = true;
    private $devMode = false;


    private $mailer;
    private $doctrine;


    
    
    
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


    public function __construct(
        RequestStack $requestStack, 
        MailerInterface $mailer,
        ManagerRegistry $doctrine,
        ){


        $this->session = $requestStack->getSession();
        $this->mailer = $mailer;
        $this->doctrine = $doctrine;
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
        $this->VerificarNotificaciones();
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


    public function enviarEmail($mail, $asunto, $mensajeHtml){
        

        $email = (new Email())
        ->from('info@vacunassist.com.ar')
        ->to($mail)
        ->subject($asunto)
        // ->text('Texto de prueba para el envio de mails')
        ->html($mensajeHtml);

        $this->mailer->send($email);

    }

    public function VerificarNotificaciones(){

        $em = $this->doctrine->getManager();
        $admin = $em->getRepository(Usuarios::class)->findOneByMail('admin@gmail.com');
        $fechaActualizacion = date_format($admin->getFechaBaja(), "d-m-Y");
        $hoy =  date_format(new DateTime(), "d-m-Y") ;
        if ($fechaActualizacion != $hoy){
            $this->enviarNotificaciones();
            $admin->setFechaBaja(new DateTime());
            $em->flush();
        }
    }



    // 
    public function enviarNotificaciones(){

        $em = $this->doctrine->getManager();

        
        $hoy = date("d-m-Y");
        
        $maniana = new DateTime(date("d-m-Y",strtotime($hoy ."+ 1 days"))); 
        
        $turnosDeManiana =  $em->getRepository(Turnos::class)->findTurnosPendientesByDate($maniana);

        foreach($turnosDeManiana as $turno){

            $this->enviarRecordatorio($turno);
        }
        
    }

    public function enviarRecordatorio($turno, $diasAntes = 1){

        $em = $this->doctrine->getManager();
        $paciente =  $em->getRepository(Pacientes::class)->findOneById($turno->getPacienteId());
        $mailPaciente = $paciente->getMail();

        $vacunatorio = $em->getRepository(Vacunatorios::class)->findOneById($turno->getVacunatorioId());
        $vacuna = $em->getRepository(Vacunas::class)->findOneById($turno->getVacunaId());



        
        $notificaciones = $em->getRepository(Notificaciones::class)->findNotificacionesByTurnoIdAndAntelacion($turno->getId(),$diasAntes );
        
        if (count($notificaciones) == 0){

            
            
            $asunto = "Recordario de turno de Vacunacion en VacunasSist";
            
            $mensajeHtml = "<p>Estimado/a " . $paciente->getNombre() . ' ' . $paciente->getApellido() . ", le recordamos que tiene asignado un turno de vacunación contra " . $vacuna->getNombre() . " para <b> mañana  ". date_format($turno->getFecha(), "d-m-Y")  . "</b> .</p>" ;
            $mensajeHtml .= "<br> <p> Centro de vacunacion ". $vacunatorio->getNombre() ." ubicado en: " . $vacunatorio->getDireccion() ." <br> Saludos Cordiales <br> VacunasSist </p>";
            
            $this->enviarEmail($mailPaciente, $asunto, $mensajeHtml);
            
            
            $notificacion = new Notificaciones();
            $notificacion->setTurnoId($turno->getId());
            $notificacion->setAntelacion($diasAntes);
            $notificacion->setLeida(false);
            $em->persist($notificacion);
            
        }
            
            
            
        }
        
        


}