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
        $barbero = $barberosModel->where('nombre', $username)->first();
    
       
        if ($barbero && password_verify($password, $barbero['password'])) {
            // Credenciales válidas
            $sessionData = [
                'username' => $barbero['nombre'], 
                'isLoggedIn' => true,
                'id_barbero' => $barbero['id_barbero'] 
                // 'rol' => $barbero['rol'] // a futuro cambiarlo 
            ];
            $session->set($sessionData);
            
            return redirect()->to(site_url('admin')); 
        
        } else {
            
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
        if (!session()->get('isLoggedIn')) { 
            return redirect()->to(site_url('login')); 
        }

        $contraseñaActual = $this->request->getPost('contraseñaActual');
        $nuevaContraseña = $this->request->getPost('nuevaContraseña');
        // Hasheamos la *nueva* contraseña
        $hashPasword = password_hash($nuevaContraseña, PASSWORD_BCRYPT);
        
        // Validar las contraseñas
        if ($contraseñaActual && $nuevaContraseña) {
            $barberosModel = new Barberos_db();
            
            
            $username = session()->get('username');
            $barbero = $barberosModel->where('nombre', $username)->first(); 

            // Verificamos si la contraseña *actual* es correcta
            if ($barbero && password_verify($contraseñaActual, $barbero['password'])) {
                
                // Actualizar la contraseña con el HASH
                $barberosModel->update($barbero['id_barbero'], ['password' => $hashPasword]);
                
                session()->setFlashdata('success', 'Contraseña cambiada con éxito.');
            } else {
                session()->setFlashdata('error', 'La contraseña actual es incorrecta.');
            }
        }

        return redirect()->to(site_url('admin/cambiarPassword'));
    }

    /**
     * ¡NUEVA FUNCIÓN!
     * Procesa la solicitud del modal "Olvidé Contraseña"
     */
    public function procesarOlvidoPassword()
    {
        // 1. Obtener datos del POST
        $usuario = $this->request->getPost('usuario');
        $nueva = $this->request->getPost('nueva_password');
        $confirmar = $this->request->getPost('confirmar_password');

        // 2. Validar que las contraseñas coincidan
        if ($nueva !== $confirmar) {
            return redirect()->to(site_url('login'))->with('error', 'Las contraseñas no coinciden. Inténtalo de nuevo.');
        }
        
        // 3. Encontrar al usuario
        $barberosModel = new Barberos_db();
        $barbero = $barberosModel->where('nombre', $usuario)->first();

        // 4. Si el usuario no existe, mostrar error
        if (!$barbero) {
            return redirect()->to(site_url('login'))->with('error', 'El usuario "' . esc($usuario) . '" no fue encontrado.');
        }

        // 5. Si todo está bien, hashear la nueva contraseña y actualizar
        $hashPasword = password_hash($nueva, PASSWORD_BCRYPT);
        $barberosModel->update($barbero['id_barbero'], ['password' => $hashPasword]);

        // 6. Redirigir al login con mensaje de éxito
        return redirect()->to(site_url('login'))->with('success', '¡Contraseña actualizada con éxito! Ya puedes iniciar sesión.');
    }
}
?>