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
                $email = $this->input->post('email', true);
                $data = [
                    'name' => htmlspecialchars($this->input->post('name', true)),
                    'email' => htmlspecialchars($email),
                    'image' => "default.jpg",
                    'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                    'role_id' => 2,
                    'is_active' => 0,
                    'date_created' => time()
                ];

                $token = base64_encode(random_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                $this->_sendEmail($token, 'verify');
                $this->db->insert('user',$data);
                $this->db->insert('user_token',$user_token);


                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                Code Aktived has ben send to your email, activated now!
                                                            </div>');
                redirect('auth');
            }
            
        }

        private function _sendEmail($token, $type){
            $this->load->library('email');

            $config = array();
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'ssl://smtp.googlemail.com';
            $config['smtp_user'] = 'appcilogin@gmail.com';
            $config['smtp_pass'] = 'Anonymous123';
            $config['smtp_port'] = 465;
            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $this->email->initialize($config);

            $this->email->set_newline("\r\n");
            $this->email->from('appcilogin@gmail.com','Ade Kurniawan');
            $this->email->to($this->input->post('email'));

            if($type == 'verify'){
                $this->email->subject('Account Verification');
                $this->email->message('Click this link to verify your account : <a href="' .base_url() .'auth/verify?email='. $this->input->post('email') . '&token='.urlencode($token).'">Activated</a>');
            }else if($type = 'forgot'){
                $this->email->subject('Account Verification');
                $this->email->message('Click this link to reset your password : <a href="' .base_url() .'auth/resetpassword?email='. $this->input->post('email') . '&token='.urlencode($token).'">Reset Password</a>');
            }

            if($this->email->send()){
                return true;
            }else{
                $this->email->print_debugger();
                die;
            }
        }

        public function verify(){
            $email = $this->input->get('email');
            $token = $this->input->get('token');

            $user = $this->db->get_where('user',['email' => $email])->row_array();
            if($user){
                $user_token = $this->db->get_where('user_token',['token' => $token])->row_array();
                if($user_token){
                    if(time()- $user_token['date_created'] < (60*60*24)){
                        $this->db->set('is_active',1);
                        $this->db->where('email', $email);
                        $this->db->update('user');

                        $this->db->delete('user_token', ['email' => $email]);
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">'.$email.' has been verified
                                                            </div>');
                        redirect('auth');
                    }else{
                        $this->db->delete('user', ['email' => $email]);
                        $this->db->delete('user_token', ['email' => $email]);
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                Token Expired!
                                                            </div>');
                        redirect('auth');
                    }
                }else{
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                Account activated failed! Wrong Token!
                                                            </div>');
                    redirect('auth');
                }
            }else{
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                Account activated failed! Wrong Email!
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

        public function forgotpassword(){
            
            $this->form_validation->set_rules('email', 'Email','required|trim|valid_email');

            if($this->form_validation->run()==false){
                $data['title'] = 'Forgot Password';
                $this->load->view('templates/auth_header',$data);
                $this->load->view('auth/forgotpassword');
                $this->load->view('templates/auth_footer');
            }else{
                $email = $this->input->post('email');
                $user = $this->db->get_where('user',['email'=>$email, 'is_active' => 1])->row_array();

                if($user){
                    $token = base64_encode(random_bytes(32));
                    $user_token = [
                        'email' => $email,
                        'token' => $token,
                        'date_created' => time()
                    ];

                    $this->db->insert('user_token',$user_token);
                    $this->_sendEmail($token,'forgot');
                    $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">
                                                                Please check your email to reset password!
                                                            </div>');
                                                            redirect('auth/forgotpassword');
                }else{
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                Your account not found or not activated!
                                                            </div>');
                    redirect('auth/forgotpassword');
                }
            }
        }

        public function resetpassword(){
            $email= $this->input->get('email');
            $token= $this->input->get('token');

            $user = $this->db->get_where('user',['email' => $email])->row_array();
            if($user){
                $user_token = $this->db->get_where('user_token',['token'=>$token])->row_array();
                if($user_token){
                    if(time()- $user_token['date_created'] < (60*60*24)){
                        $this->session->set_userdata('reset_email',$email);
                        $this->changePassword();
                    }else{
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                Token Expired!
                                                            </div>');
                         redirect('auth/forgotpassword');
                    }
                }else{
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                Wrong Token!
                                                            </div>');
                    redirect('auth/forgotpassword');
                }
            }else{
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                Reset password fail!
                                                            </div>');
                redirect('auth/forgotpassword');
            }
        }

        public function changePassword(){
            if(!$this->session->userdata('reset_email')){
                redirect('auth'); 
            }
            $this->form_validation->set_rules('password1', 'New Password', 'required|trim|min_length[8]|matches[password2]');
            $this->form_validation->set_rules('password2', 'Cofirm Password', 'required|trim|min_length[8]|matches[password1]');

            if($this->form_validation->run()==false){
                $data['title'] = 'Forgot Password';
                $this->load->view('templates/auth_header',$data);
                $this->load->view('auth/changepassword');
                $this->load->view('templates/auth_footer');
            }else{
                $password = password_hash($this->input->post('password1'),PASSWORD_DEFAULT);
                $email = $this->session->userdata['reset_email'];

                $this->db->update('user',['password' => $password],['email' => $email]);

                $this->session->unset_userdata('reset_email');
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                                                                Reset password succes!
                                                            </div>');
                redirect('auth');
            }
        }
    }

?>