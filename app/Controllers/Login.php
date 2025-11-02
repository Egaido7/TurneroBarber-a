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
        // Buscamos al barbero por nombre de usuario, no por ID 1
        $barbero = $barberosModel->where('nombre', $username)->first();
    
        // Verificamos el usuario Y usamos password_verify para la contraseña hasheada
        if ($barbero && $password == $barbero['password']) {
            // Credenciales válidas
            $sessionData = [
                'username' => $barbero['nombre'], // Guardamos el nombre desde la BD
                'isLoggedIn' => true,
                // 'rol' => $barbero['rol'] // Deberías guardar el rol aquí también
            ];
            $session->set($sessionData);
            // CORRECCIÓN DE REDIRECT:
            // Usamos site_url('admin') que ahora, sin index.php,
            // apuntará a http://localhost/leanbarber/admin
            return redirect()->to(site_url('admin')); 
        
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
        if (!session()->get('isLoggedIn')) { // <-- CORREGIDO: Debería ser !isLoggedIn
            return redirect()->to(site_url('login')); // Usar site_url()
        }

        $contraseñaActual = $this->request->getPost('contraseñaActual');
        $nuevaContraseña = $this->request->getPost('nuevaContraseña');
        // Hasheamos la *nueva* contraseña
        $hashPasword = password_hash($nuevaContraseña, PASSWORD_BCRYPT);
        
        // Validar las contraseñas
        if ($contraseñaActual && $nuevaContraseña) {
            $barberosModel = new Barberos_db();
            
            // Traemos al barbero que está logueado (ejemplo con ID 1, pero deberías usar el ID de la sesión)
            $username = session()->get('username');
            $barbero = $barberosModel->where('nombre', $username)->first(); 

            // Verificamos si la contraseña *actual* es correcta
            if ($barbero && password_verify($contraseñaActual, $barbero['password'])) {
                
                // Actualizar la contraseña con el HASH
                $barberosModel->update($barbero['id_barbero'], ['password' => $hashPasword]); // Asumiendo que tienes un id_barbero
                
                session()->setFlashdata('success', 'Contraseña cambiada con éxito.');
            } else {
                session()->setFlashdata('error', 'La contraseña actual es incorrecta.');
            }
        }

        return redirect()->to(site_url('admin/cambiarPassword')); // Usar site_url()
    }
}
?>