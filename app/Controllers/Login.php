<?php

namespace App\Controllers;
use App\Models\Barberos_db;
use PhpParser\Node\Expr\AssignOp\Mod;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function login(){
        $session = session();
        $username = $this->request->getPost('usuario');
        $password = $this->request->getPost('password');
        $barberosModel = new Barberos_db();
        $barbero = $barberosModel->traerBarbero(1);
        
        $validUsername = $barbero['nombre'];
        $validPassword = $barbero['password'];

        if ($username === $validUsername && $password === $validPassword) {
            // Credenciales válidas
            $sessionData = [
                'username' => $username,
                'isLoggedIn' => true,
            ];
            $session->set($sessionData);
            return redirect()->to('admin/principal');
        } else {
            // Credenciales inválidas
            $session->setFlashdata('error', 'Usuario o contraseña incorrectos.');
            return redirect()->back();
        }
    }

    public function admin()
    {
        // Verificar si el usuario está logueado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('admin/principal');
    }

    public function cambiarPassword()
    {
        // Verificar si el usuario está logueado
        if (session()->get('isLoggedIn')) {
            return redirect()->to('admin/principal');
        }

        $contraseñaActual = $this->request->getPost('contraseñaActual');
        $nuevaContraseña = $this->request->getPost('nuevaContraseña');
        $hashPasword = password_hash($nuevaContraseña, PASSWORD_BCRYPT);
                // Validar las contraseñas
        if ($contraseñaActual && $nuevaContraseña) {
            $barberosModel = new Barberos_db();
            $barbero = $barberosModel->traerBarbero(1); // Ejemplo: traer barbero con ID 1

            if ($barbero && $barbero['password'] === $contraseñaActual) {
                // Actualizar la contraseña
                $barberosModel->actualizarPassword(1, $nuevaContraseña);
                session()->setFlashdata('success', 'Contraseña cambiada con éxito.');
            } else {
                session()->setFlashdata('error', 'La contraseña actual es incorrecta.');
            }
        }

        return redirect()->to('admin/cambiar_password');
    }
}
?>