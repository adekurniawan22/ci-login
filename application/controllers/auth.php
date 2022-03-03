<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Auth extends CI_Controller {

        public function __construct(){
            parent::__construct();
            $this->load->library('form_validation');
        }

        public function index(){

            if($this->session->userdata('email')){
                redirect('user');
            }
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[8]',[
                'min_length' => 'Password to short!'
            ]);
            if($this->form_validation->run() == false){
                $data['title'] = 'Login';
                $this->load->view('templates/auth_header',$data);
                $this->load->view('auth/login');
                $this->load->view('templates/auth_footer');
            }else{
                $this->_login();
            }
        }

        private function _login(){
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $user = $this->db->get_where('user', ['email' => $email])->row_array();

            if($user){
                if($user['is_active'] == 1){
                    if(password_verify($password,$user['password'])){
                        $data = [
                            'email' => $user['email'],
                            'role_id' => $user['role_id']
                        ];

                        $this->session->set_userdata($data);
                        
                        if($user['role_id'] == 1){
                            redirect('admin');
                        }else{
                            redirect('user');
                        }
                    }else{
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                Your password wrong!
                                                            </div>');
                        redirect('auth');
                    }
                }else{
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                Your data not acvtived!
                                                            </div>');
                    redirect('auth');
                }
            }else{
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                Your data not found in database!
                                                            </div>');
                redirect('auth');
            }
        }

        public function register(){
            if($this->session->userdata('email')){
                redirect('user');
            }
            $this->form_validation->set_rules('name','Name','required|trim');
            $this->form_validation->set_rules('email','Email', 'required|trim|valid_email|is_unique[user.email]',[
                'is_unique' => 'This email has already registered!'
            ]);
            $this->form_validation->set_rules('password1','Password', 'required|trim|matches[password2]|min_length[8]',[
                'min_length' => 'Password too short!',
                'matches' => 'Password dont match'
            ]);
            $this->form_validation->set_rules('password1','Password', 'required|trim|matches[password1]|min_length[8]');

            if($this->form_validation->run() == false){
                $data['title'] = 'Register';
                $this->load->view('templates/auth_header',$data);
                $this->load->view('auth/register');
                $this->load->view('templates/auth_footer');
            }else{
                $data = [
                    'name' => htmlspecialchars($this->input->post('name', true)),
                    'email' => htmlspecialchars($this->input->post('email', true)),
                    'image' => "default.jpg",
                    'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                    'role_id' => 2,
                    'is_active' => 1,
                    'date_created' => time()
                ];

                $this->db->insert('user',$data);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                                                                Your account has been created!
                                                            </div>');
                redirect('auth');
            }
            
        }

        public function logout(){
            $this->session->unset_userdata('email');
            $this->session->unset_userdata('role_id');
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                                                                Your account has been logged out!
                                                            </div>');
            redirect('auth');
        }

        public function blocked(){
            $this->load->view('auth/blocked');
        }
    }

?>