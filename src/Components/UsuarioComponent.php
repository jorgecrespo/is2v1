<?php
namespace App\Components;

use App\Service\CustomService;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('usuario')]
class UsuarioComponent
{
    public string $rol;
    public string $user;

    public function  __construct( CustomService $cs){

        $sesion = $cs->getUser();
        $this->user = $sesion['user'];
        $this->rol = $sesion['rol'];
        // dd($this->user);
    }
}