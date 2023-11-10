<?php

class AdminUserController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('AdminUser');
    }

    public function index()
    {
        $session = new AdminSession();

        $users = $this->model->getUsers();

        if ($session->getLogin()) {

            $data = [
                'title' => 'Administración de usuarios',
                'menu' => false,
                'admin' => true,
                'data' => $users,
            ];

            $this->view('admin/users/index', $data);

        } else {

            header('location:' . ROOT . 'admin');

        }

    }

    public function create($dataForm = [], $errors = [])
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $errors = [];
            $data = [
                'title' => 'Administración de Usuarios - Alta',
                'menu' => false,
                'admin' => true,
                'errors' => $errors,
                'data' => $dataForm,
            ];
           
            $this->view('admin/users/create', $data);

        }

        public function store()
        {
            $errors = [];

            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password1 = $_POST['password1'] ?? '';
            $password2 = $_POST['password2'] ?? '';
                    
            $dataForm = [
                'name' => $name,
                'email' => $email,
                'password' => $password1,
            ];
            if (empty($name)) {
                array_push($errors, 'El nombre de usuario es requerido');
            }
            if (empty($email)) {
                array_push($errors, 'El correo electrónico de usuario es requerido');
            }
            if (empty($password1)) {
                array_push($errors, 'La clave de acceso es requerida');
            }
            if (empty($password2)) {
                array_push($errors, 'La verificación de clave es requerida');
            }
            if ($password1 != $password2) {
                array_push($errors, 'Las claves no coinciden');
            }
            if (!$errors) {

                if ($this->model->createAdminUser($dataForm)) {
                    header("location:" . ROOT . 'adminUser');

            } else {

                $data = [
                    'title' => 'Error en la creación de un usuario administrador',
                    'menu' => false,
                    'admin' => true,
                    'errors' => $errors,
                    'data' => $dataForm,
                    'errors' => [],
                    'subtitle' => 'Error al crear un nuevo usuario administrador',
                    'text' => 'Se ha producido un error durante el proceso de creación de un usuario administrador',
                    'color' => 'alert-danger',
                    'url' => 'adminUser',
                    'colorButton' => 'btn-danger',
                    'textButton' => 'Volver',
                ];

                $this->view('mensaje', $data);
            }
        } else {
        
            $this->create($dataForm, $errors);
        }
    }

    public function edit($id, $errors = [])
    {
        $user = $this->model->getUserById($id);
        $status = $this->model->getConfig('adminStatus');

        $data = [
            'title' => 'Administración de Usuarios - Editar',
            'menu' => false,
            'admin' => true,
            'data' => $user,
            'status' => $status,
            'errors' => $errors,
        ];

        $this->view('admin/users/update', $data);
    }

    public function update($id)
    {
        $errors = [];

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password1 = $_POST['password1'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $status = $_POST['status'] ?? '';

        if ($name == '') {
            array_push($errors, 'El nombre del usuario es requerido');
        }
        if ($email == '') {
            array_push($errors, 'El email es requerido');
        }
        if ($status == '') {
            array_push($errors, 'Selecciona un estado para el usuario');
        }
        if (!empty($password1) || !empty($password2)) {
            if ($password1 != $password2) {
                array_push($errors, 'Las contraseñas no coinciden');
            }
        }

        if (!$errors) {
            $data = [
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'password' => $password1,
                'status' => $status,
            ];
            $errors = $this->model->setUser($data);
            if (!$errors) {
                header("location:" . ROOT . 'adminUser');
            } else {
                $this->edit($id, $errors);
            }
        }
    }
    public function delete($id, $errors = [])
    {

        $user = $this->model->getUserById($id);
        $status = $this->model->getConfig('adminStatus');

        $data = [
            'title' => 'Administración de Usuarios - Eliminación',
            'menu' => false,
            'admin' => true,
            'data' => $user,
            'status' => $status,
            'errors' => $errors,
        ];

        $this->view('admin/users/delete', $data);
    }

    public function destroy($id)
    {
        $errors = $this->model->delete($id);

        if (!$errors) {
            header('location:' . ROOT . 'adminUser');
            return;
        }

        $this->delete($id, $errors);
    }
    
    public function destroy($id)
    {
        $errors = $this->model->delete($id);

        if (!$errors) {
            header('location:' . ROOT . 'adminUser');
            return;
        }

        $this->delete($id, $errors);
    }
}