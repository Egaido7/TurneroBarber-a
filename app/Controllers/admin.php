<?php

namespace App\Controllers;
use App\Models\Barberos_db;
use PhpParser\Node\Expr\AssignOp\Mod;

class Admin extends BaseController
{
   public function dashboard()
    {
        // Verificar que el usuario está autenticado como admin
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        $section = $this->request->getGet('section') ?? 'turnos';

        return view('admin/dashboard', ['section' => $section]);
    }

    public function agregarPeluquero()
    {
         if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }
        $data = $this->request->getPost();
        // Aquí guardarías el peluquero en la BD
        return redirect()->to(site_url('admin?section=peluqueros'))->with('mensaje', 'Peluquero agregado correctamente');
    }

    public function agregarServicio()
    {
          if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }
        $data = $this->request->getPost();
        // Aquí guardarías el servicio en la BD
        return redirect()->to(site_url('admin?section=servicios'))->with('mensaje', 'Servicio agregado correctamente');
    }

}