<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Selamat Datang di Aplikasi Saya',
            'heading' => 'Halo! Ini Aplikasi Pertama Saya',
            'content' => 'Ini adalah halaman utama yang dibuat dengan CodeIgniter 4 dan Bootstrap 5.'
        ];
        
        return view('home_view', $data);
    }
    
    public function about()
    {
        $data = [
            'title' => 'Tentang Kami',
            'page_name' => 'About'
        ];
        
        return view('about_view', $data);
    }
    
    public function contact()
    {
        $data = [
            'title' => 'Kontak',
            'page_name' => 'Contact'
        ];
        
        return view('contact_view', $data);
    }
}