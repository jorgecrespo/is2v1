<?php
namespace App\Components;

use App\Service\CustomService;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('usuario')]
class UsuarioComponent
{
    public string $rol;
    public string $user;
    public string $env;
    public string $homePage;


    public function  __construct( CustomService $cs){

        $sesion = $cs->getUser();
        $this->user = $sesion['user'];
        $this->rol = $sesion['rol'];
        $this->env = $cs->isDevMode() ? 'dev' : 'prod';
        $this->homePage = $cs->isDevMode() == 'dev' ? '/': $cs->getHomePageByUser() ;
        // dd($this->user);
    }
}