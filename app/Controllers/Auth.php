<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
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
        // Load the form helper to handle validation
        helper('form');

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Set validation rules
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        // Set custom error messages
        $errors = [
            'username' => [
                'required' => 'The username field is required.'
            ],
            'password' => [
                'required' => 'The password field is required.'
            ]
        ];

        // Validate the input data
        if (!$this->validate($rules, $errors)) {
            // If validation fails, store the errors in the session and redirect back to the login page
            return redirect()->to('login')->withInput()->with('errors', $this->validator->getErrors());
        } else {
            // Add your login logic here (e.g., checking credentials, validating user, etc.)
            // For demonstration purposes, we'll just redirect back to the login page.
            return redirect()->to('login');
        }
    }
}
