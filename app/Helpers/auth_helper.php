<?php

if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        $session = \Config\Services::session();
        return $session->get('logged_in') === true;
    }
}

if (!function_exists('get_user_data')) {
    function get_user_data($key = null)
    {
        $session = \Config\Services::session();
        
        if (!$session->get('logged_in')) {
            return null;
        }
        
        if ($key) {
            return $session->get($key);
        }
        
        return [
            'id' => $session->get('user_id'),
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'email' => $session->get('email'),
            'role' => $session->get('role')
        ];
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return get_user_data('role') === 'admin';
    }
}

if (!function_exists('is_staff')) {
    function is_staff()
    {
        return get_user_data('role') === 'staff';
    }
}