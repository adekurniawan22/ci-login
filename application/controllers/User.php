<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class User extends CI_Controller {
        public function __construct(){
            parent::__construct();
            $this->load->library('upload');
            is_logged_in();
        }

        public function index(){
            
                $data['title'] = "My Profile";
                $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

                $this->load->view('templates/header', $data);
                $this->load->view('templates/sidebar', $data);
                $this->load->view('templates/topbar', $data);
                $this->load->view('user/index', $data);
                $this->load->view('templates/footer', $data);
            
            ;
        }   

        public function edit(){
                $data['title'] = "Edit Profile";
                $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();

                $this->form_validation->set_rules('name', 'Name', 'required|trim');

                if($this->form_validation->run() == false){
                    $this->load->view('templates/header', $data);
                    $this->load->view('templates/sidebar', $data);
                    $this->load->view('templates/topbar', $data);
                    $this->load->view('user/edit', $data);
                    $this->load->view('templates/footer', $data);
                }else{
                    $name = $this->input->post('name');
                    $id = $this->input->post('id');
                    $cekimage = $_FILES['image']['name'];
                    $image = $data['user']['image'];
                    
                    if($cekimage){
                        $config['allowed_types'] = 'gif|jpg|png';
                        $config['max_size'] = '5048';
                        $config['upload_path'] = './assets/img/profile/';
                        $this->load->library('upload',$config);
                        $this->upload->initialize($config);

                        if($this->upload->do_upload('image')){
                            if($image != "default.jpg"){
                                $path = 'assets/img/profile/';
                                unlink(FCPATH.$path.$image);
                            }
                            $image = $this->upload->data('file_name');
                        }else{
                            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . $this->upload->display_errors() . '</div>');
                            redirect('user');
                        }
                    }

                
                    if($data['user']['name'] == $name AND $data['user']['image'] == $image){
                        redirect('user');
                    }else{
                        $this->db->update('user',[
                                                    'name' => $name,
                                                    'image' => $image],['id' => $id]);
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                                                                    Your Data Edited!
                                                                </div>');
                        redirect('user');
                    }
                }
                
            
        }

        public function changepassword(){
            
            $data['title'] = "Change Password";
            $data['user'] = $this->db->get_where('user',['email' => $this->session->userdata('email')])->row_array();
            
            $this->form_validation->set_rules('currentPassword','Current Password','required|trim|min_length[8]');
            $this->form_validation->set_rules('newPassword1','New Password','required|trim|min_length[8]|matches[newPassword2]');
            $this->form_validation->set_rules('newPassword2','Confirm Password','required|trim|min_length[8]|matches[newPassword1]');

            if($this->form_validation->run()==false){
                $this->load->view('templates/header', $data);
                $this->load->view('templates/sidebar', $data);
                $this->load->view('templates/topbar', $data);
                $this->load->view('user/changepassword', $data);
                $this->load->view('templates/footer');
            }else{
                $currentPassword = $this->input->post('currentPassword');
                $newPassword = $this->input->post('newPassword1');
                if(!password_verify($currentPassword,$data['user']['password'])){
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                    Wrong Current Password!
                                                                </div>');
                    redirect('user/changepassword');
                }else{
                    if($currentPassword == $newPassword){
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                                                                New Password Cannot be the same!
                                                            </div>');
                        redirect('user/changepassword');
                    }else{
                        $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
                        $id= $data['user']['id'];
                        $this->db->set('password',$password_hash);
                        $this->db->where('id',$id);
                        $this->db->update('user');
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                                                                Password Changes!
                                                            </div>');
                        redirect('user/changepassword');
                    }
                }
            }
        
        ;
    }  
    }
?>