<?php

class AdminShopController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('AdminShop');
    }

    public function index()
    {
        $session = new AdminSession();

        if ($session->getLogin()) {

            $data = [
                'title' => 'Administración - Inicio',
                'menu' => false,
                'admin' => true,
                'subtitle' => 'Administración de la tienda',
            ];

            $this->view('admin/shop/index', $data);

        } else {
            header('location:' . ROOT . 'admin');
        }

        public function logout()
        {
            $session = new AdminSession();
            $session->logout();
            header('location:' . ROOT . 'admin');
        }

    }
}