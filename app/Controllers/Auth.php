<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_user;

class Auth extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new M_user();
        helper(['form', 'url']);
    }
    public function index()
    {
        //
    }

    function login()
    {
        return view('auth/login');
    }

    public function process()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Set validation rules
        $rules = [
            'username' => 'required|alpha_dash|min_length[5]',
            'password' => 'required|min_length[5]'
        ];

        // Validate the input data
        if (!$this->validate($rules)) {
            return redirect()->to('login')->withInput()->with('errors', $this->validator->getErrors());
        } else {
            $data = $this->model
            ->groupStart()
            ->where('username', $username)
            ->orWhere('email', $username)
            ->groupEnd()
            ->where('deleted_at', null)
            ->first();
            if ($data) {
                $verify = password_verify($password, $data['password']);
                if ($verify) {
                    $sess_data = [
                        'id'        => $data['id'],
                        'email'     => $data['email'],
                        'username'  => $data['username'],
                        'fullname'  => $data['fullname'],
                        'image'     => $data['img'],
                        'is_admin'  => $data['is_admin'],
                        'theme'     => $data['theme'],
                        'logged_in' => true
                    ];
                    $this->session->set($sess_data);
                    return redirect()->to('/dashboard');
                } else {
                    $this->session->setFlashdata('msg', 'wrong password');
                    return redirect()->to('login')->withInput();
                }
            }
            $this->session->setFlashdata('msg', 'username not found');
            return redirect()->to('login')->withInput();
        }
    }

    function testInsert()
    {
        $data = [
            'username'  => 'admin',
            'password'  => password_hash('admin', PASSWORD_BCRYPT),
            'fullname'  => 'Admin rahat',
            'email'     => 'admin@gmail.com',
            'img'       => 'profile.png',
            'is_admin'  => 1,
            'theme'     => 'light'
        ];
        
        if (!$this->model->validate($data)) {
            $return = [
                'status' => 'error',
                'title'  => 'Error',
                'message'=> $this->model->validation->getErrors()
            ];
            echo json_encode($return);
            return false;
        }
        try {
            $this->model->save($data);
            $return = [
                'status'    => 'success',
                'title'     => 'Success',
                'message'   => 'Data berhasil disimpan'
            ];
        } catch (\Exception $th) {
            $return = [
                'status'    => 'error',
                'title'     => 'Error',
                'message'   => $th->getMessage()
            ];
        }
        echo json_encode($return);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }

    function testDelete($id) {
        $this->model->delete($id);
    }

    function showAllUser()
    {
        $data = $this->model->withDeleted()->findAll();
        dd($data);
    }

}