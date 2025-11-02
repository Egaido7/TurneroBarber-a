<?php

namespace App\Controllers;
use App\Models\Barberos_db;
use PhpParser\Node\Expr\AssignOp\Mod;

class Admin extends BaseController
{
   public function dashboard()
    {
        // Verificar que el usuario está autenticado como admin
        $user = session()->get('user');
        if (!$user || $user['rol'] !== 'admin') {
            return redirect()->to('/login');
        }

        $section = $this->request->getGet('section') ?? 'turnos';

        return view('admin/dashboard', ['section' => $section]);
    }

    public function agregarPeluquero()
    {
        $data = $this->request->getPost();
        // Aquí guardarías el peluquero en la BD
        return redirect()->to('/admin?section=peluqueros')->with('mensaje', 'Peluquero agregado correctamente');
    }

    public function agregarServicio()
    {
        $data = $this->request->getPost();
        // Aquí guardarías el servicio en la BD
        return redirect()->to('/admin?section=servicios')->with('mensaje', 'Servicio agregado correctamente');
    }

}