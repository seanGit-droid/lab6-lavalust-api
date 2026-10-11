<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class StudentController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $data['title'] = "Student Home Page";
        $this->call->view('student/index', $data);
    }

    public function profile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Student information passed to the view
        $data = [
            'title'      => 'Student Profile',
            'student_id' => 'MCC2024-00146',
            'name'       => 'Sean Ivan C. Ramiscal',
            'course'     => 'BS Information Technology',
            'year'       => '3rd Year',
            'section'    => 'BSIT-3F2',
            'email'      => 'seanivanramiscal@.com',
            'skills'     => 'PHP, LavaLust Framework, Docker, Web Development'
        ];

        $this->call->view('student/profile', $data);
    }

    // Helper method to simulate granting access
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['student_access'] = true;
        redirect('student/profile');
    }

    // Helper method to revoke access
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['student_access']);
        redirect('student');
    }
}