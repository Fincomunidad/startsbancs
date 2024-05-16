<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
	}

	// redirect if needed, otherwise display the user list
	public function index()
	{

		if (!$this->ion_auth->logged_in())
		{
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		elseif (!$this->ion_auth->is_admin()) // remove this elseif if you want to enable this for non-admins
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('Deberia ser administrador para visualizar ésta página.');
		}
		else
		{
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();
			foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}
			
			$this->_render_page('auth/index', $this->data);
		}
	}

	// log the user in
	public function login()
	{
		$this->data['title'] = $this->lang->line('login_heading');

		//validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'required');
		$this->form_validation->set_rules('base', str_replace(':', '', $this->lang->line('login_base_label')), 'required');

		if ($this->form_validation->run() == true)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');

			$base = strtoupper($this->input->post('base'));
			if ($base === 'FINCOMUNIDAD' || $base === 'BANCOMUNIDAD' || $base === 'AMA' || $base === 'IMPULSO') 
			{
				if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
				{
					// Aqui se asigna el esquema de la base de datos 
					$this->session->set_userdata('esquema', substr(strtolower($base),0,3).'.');
					$this->session->set_userdata('esquemaname', $base);
					//if the login is successful
					//redirect them back to the home page
					$this->session->set_flashdata('message', $this->ion_auth->messages());
					
					if ($this->session->userdata('change_password') !==true) {
						redirect('/', 'refresh');
					}else {
						redirect('auth/change_password', 'refresh');
					}
				}
				else
				{
					// if the login was un-successful
					// redirect them back to the login page
					$identity = $this->input->post('identity');
					$messageAttemps = $this->ion_auth->login_attempts_exceeded($identity);;
					$this->session->set_flashdata('message', $this->ion_auth->errors().$messageAttemps);
					redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
				}			   			
			} 
			else 
			{
				$this->session->set_flashdata('message', 'No se ha podido iniciar base de sesión');
				redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}

		}
		else
		{

			if (!$this->ion_auth->logged_in()) {
				// the user is not logging in so display the login page
				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['identity'] = array('name' => 'identity',
					'id'    => 'identity',
					'type'  => 'email',
					'value' => $this->form_validation->set_value('identity'),
				);
				$this->data['password'] = array('name' => 'password',
					'id'   => 'password',
					'type' => 'password',
				);
				$this->data['base'] = array('name' => 'base',
					'id'    => 'base',
					'type'  => 'text',
					'value' => $this->form_validation->set_value('base'),
				);

				$this->_render_page('auth/login', $this->data);
			} else {

				redirect('/', 'refresh');				
			}
		}
	}

	// log the user out
	public function logout()
	{
		$this->data['title'] = "Logout";

		// log the user out
		$logout = $this->ion_auth->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('auth/login', 'refresh');



	}

	// change password
	public function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]|callback_check_password');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			// display the form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name'    => 'new',
				'id'      => 'new',
				'type'    => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name'    => 'new_confirm',
				'id'      => 'new_confirm',
				'type'    => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			// render
			$this->_render_page('auth/change_password', $this->data);
		}
		else
		{
			$identity = $this->session->userdata('identity');

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$this->logout();
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/change_password', 'refresh');
			}
		}
	}

	// forgot password
	public function forgot_password()
	{
		// setting validation rules by checking whether identity is username or email
		if($this->config->item('identity', 'ion_auth') != 'email' )
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_identity_label'), 'required');
		}
		else
		{
		   $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
		}


		if ($this->form_validation->run() == false)
		{
			$this->data['type'] = $this->config->item('identity','ion_auth');
			// setup the input
			$this->data['identity'] = array('name' => 'identity',
				'id' => 'identity',
			);

			if ( $this->config->item('identity', 'ion_auth') != 'email' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('auth/forgot_password', $this->data);
		}
		else
		{
			$identity_column = $this->config->item('identity','ion_auth');
			$identity = $this->ion_auth->where($identity_column, $this->input->post('identity'))->users()->row();

			if(empty($identity)) {
	            		if($this->config->item('identity', 'ion_auth') != 'email')
		            	{
		            		$this->ion_auth->set_error('forgot_password_identity_not_found');
		            	}
		            	else
		            	{
		            	   $this->ion_auth->set_error('forgot_password_email_not_found');
		            	}
		                $this->session->set_flashdata('message', $this->ion_auth->errors());
                		redirect("auth/forgot_password", 'refresh');
            		}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("auth/forgot_password", 'refresh');
			}
		}
	}

	// reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]|callback_check_password');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'type'    => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->_render_page('auth/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);

					show_error($this->lang->line('error_csrf'));

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						redirect("auth/login", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('auth/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}


	// activate the user
	public function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			// redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("auth", 'refresh');
		}
		else
		{
			// redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

	// deactivate the user
	public function deactivate($id = NULL)
	{
		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			// redirect them to the home page because they must be an administrator to view this
			return show_error('You must be an administrator to view this page.');
		}

		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();

			$this->_render_page('auth/deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			// redirect them back to the auth page
			redirect('auth', 'refresh');
		}
	}

	// create a new user
	public function create_user()
    {
		$this->load->model('Base_auth','authmodel');
        $this->data['title'] = $this->lang->line('create_user_heading');
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth', 'refresh');
        }
		$sucursales = $this->authmodel->allsucursal();
        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        $this->form_validation->set_rules('user_name', $this->lang->line('create_user_validation_name_label'), 'required');


        if($identity_column!=='email')
        {
            $this->form_validation->set_rules('identity',$this->lang->line('create_user_validation_identity_label'),'required|is_unique['.$tables['users'].'.'.$identity_column.']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        }
        else
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique['.$tables['users'].'.email'.']');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|callback_check_password');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'username'  => $this->input->post('user_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );
			$group =$this->input->post('groups');
			$groups = array(
				'group_id' => $group
				);
			$sucursal = $this->input->post('sucursal');
			$sucursales = array(
				'sucursal_id' => $sucursal
			);

			$sucursal = $this->input->post('sucursalban');
			$sucursalesban = array(
				'sucursal_id' => $sucursal
			);

			$sucursal = $this->input->post('sucursalama');
			$sucursalesama = array(
				'sucursal_id' => $sucursal
			);

			$sucursal = $this->input->post('sucursalimp');
			$sucursalesimp = array(
				'sucursal_id' => $sucursal
			);

		}




        if ($this->form_validation->run() == true && $this->ion_auth->register($identity, $password, $email, $additional_data, $groups, $sucursales, $sucursalesban, $sucursalesama ))
        {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        }
        else
        {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('last_name'),
			);
			
            $this->data['user_name'] = array(
                'name'  => 'user_name',
                'id'    => 'user_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('user_name'),
			);
			
            $this->data['identity'] = array(
                'name'  => 'identity',
                'id'    => 'identity',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('email'),
            );

			$this->data['group'] = array(
                  '0'  => 'Administrador',
                  '1' => 'Contabilidad',
                  '2'   => 'Cartera',
                  '3'   => 'PLD',
                  '4'   => 'Colmenas',
                  '5'   => 'Caja'
				);
				
			$this->data['sucursal'] = $sucursales;

            $this->data['company'] = array(
                'name'  => 'company',
                'id'    => 'company',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name'  => 'password_confirm',
                'id'    => 'password_confirm',
                'type'  => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $this->_render_page('auth/create_user', $this->data);
        }
	}
	


	// edit a user
	public function edit_user($id)
	{
		$this->load->model('Base_auth','authmodel');
		
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$sucursales = $this->authmodel->allsucursal();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();
		$currentSucursales = $this->authmodel->get_users_sucursales('fin.',$id);
		$currentSucursalesban = $this->authmodel->get_users_sucursales('ban.',$id);
		$currentSucursalesama = $this->authmodel->get_users_sucursales('ama.',$id);
		$currentSucursalesimp = $this->authmodel->get_users_sucursales('imp.',$id);

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('user_name', $this->lang->line('edit_user_validation_name_label'), 'required');

		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|callback_check_password');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'user_name'  => $this->input->post('user_name'),
					'company'    => $this->input->post('company'),
					'phone'      => $this->input->post('phone'),
					'lock'       => $this->input->post('lock'),
					'changepwd'  => $this->input->post('changepwd'),
					
				);


				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}


				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin())
				{
					//Update the groups user belongs to
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData)) {

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}

					}


					//Update the groups user belongs to
					$sucursalData = $this->input->post('sucursal');
					if (isset($sucursalData) && !empty($sucursalData)) {

						$this->ion_auth->remove_from_sucursal('', $id, 'fin.');

						foreach ($sucursalData as $grp) {
							$this->ion_auth->add_to_sucursal($grp, $id, 'fin.');
						}
					}else if (empty($sucursalData)) {
						$this->ion_auth->remove_from_sucursal('', $id, 'fin.');
					}



					//Update the groups user belongs to
					$sucursalData = $this->input->post('sucursalban');
					if (isset($sucursalData) && !empty($sucursalData)) {

						$this->ion_auth->remove_from_sucursal('', $id, 'ban.');

						foreach ($sucursalData as $grp) {
							$this->ion_auth->add_to_sucursal($grp, $id,'ban.');
						}

					}else if (empty($sucursalData)) {
						$this->ion_auth->remove_from_sucursal('', $id, 'ban.');
					}

					
					//Update the groups user belongs to
					$sucursalData = $this->input->post('sucursalama');
					if (isset($sucursalData) && !empty($sucursalData)) {

						$this->ion_auth->remove_from_sucursal('', $id, 'ama.');

						foreach ($sucursalData as $grp) {
							$this->ion_auth->add_to_sucursal($grp, $id,'ama.');
						}

					}else if (empty($sucursalData)) {
						$this->ion_auth->remove_from_sucursal('', $id, 'ama.');
					}


					$sucursalData = $this->input->post('sucursalimp');
					if (isset($sucursalData) && !empty($sucursalData)) {

						$this->ion_auth->remove_from_sucursal('', $id, 'imp.');

						foreach ($sucursalData as $grp) {
							$this->ion_auth->add_to_sucursal($grp, $id,'imp.');
						}

					}else if (empty($sucursalData)) {
						$this->ion_auth->remove_from_sucursal('', $id, 'imp.');
					}



				}

			// check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data))
			    {
					if (($data['lock'] !== 'true') == 1) 
					{
						$this->ion_auth->clear_login_attempts($user->email);
					}

					
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->messages() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('auth', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}

			    }
			    else
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->errors() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('auth', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}

			    }

			}
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['sucursales'] = $sucursales;
		$this->data['currentGroups'] = $currentGroups;
		$this->data['currentSucursales'] = $currentSucursales;
		$this->data['currentSucursalesban'] = $currentSucursalesban;
		$this->data['currentSucursalesama'] = $currentSucursalesama;
		$this->data['currentSucursalesimp'] = $currentSucursalesimp;


		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);

		$this->data['user_name'] = array(
			'name'  => 'user_name',
			'id'    => 'user_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('user_name', $user->username),
		);
		
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);
		$this->data['lock'] = $this->form_validation->set_value('lock', $user->lock);
		$this->data['changepwd'] = $this->form_validation->set_value('lock', $user->changepwd);


		$this->_render_page('auth/edit_user', $this->data);
	}

	// create a new group
	public function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth", 'refresh');
			}
		}
		else
		{
			// display the create group form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

			$this->_render_page('auth/create_group', $this->data);
		}
	}


//Edit usuario activo	
	public function edit_puser($id)
	{

		$this->load->model('Base_auth','authmodel');
		
		$this->data['title'] = $this->lang->line('edit_user_heading');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth', 'refresh');
		}else if (($this->ion_auth->user()->row()->id <> $id))
		{
			redirect('', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('user_name', $this->lang->line('edit_user_validation_name_label'), 'required');

		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|callback_check_password');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'user_name'  => $this->input->post('user_name'),
					'phone'      => $this->input->post('phone'),
				);


				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}



			// check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data))
			    {
					
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->messages() );
						redirect('auth/edit_puser/'.$id, 'refresh');
			    }
			    else
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->errors() );
						redirect('auth/edit_puser/'.$id, 'refresh');

			    }

			}
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		$this->data['user'] = $user;


		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));



		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
			'readonly'  => true,
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
			'readonly'  => true,
		);

		$this->data['user_name'] = array(
			'name'  => 'user_name',
			'id'    => 'user_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('user_name', $user->username),
			'readonly'  => true,
		);
		
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->_render_page('auth/edit_puser', $this->data);
	}





	// edit a group
	public function edit_group($id)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect('auth', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		// validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("auth", 'refresh');
			}
		}

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['group'] = $group;

		$readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

		$this->data['group_name'] = array(
			'name'    => 'group_name',
			'id'      => 'group_name',
			'type'    => 'text',
			'value'   => $this->form_validation->set_value('group_name', $group->name),
			$readonly => $readonly,
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);

		$this->_render_page('auth/edit_group', $this->data);
	}


	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
/*
	public function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}
*/

	function _render_page($view, $data=null, $render=true)
	{
		$this->viewdata = (empty($data)) ? $this->data: $data;
		$view_html = $this->load->view($view, $this->viewdata, $render);
		$supercontenido = array('content' => $view_html);
		$this->load->view('template/inicio',$supercontenido);
	}

	/*
	* Funcion valida password con Letra mayudscula minuscula numero y caracter especial
	*/
	function check_password($p){ 
		if (preg_match('/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).(?=.*[*@$?¡\-_]).{'.$this->config->item('min_password_length', 'ion_auth').'}/', $p)) return true;

		else{
		  $this->form_validation->set_message('check_password', 
			  '<span class="error">
				  <p> Contraseña requiere mínimo:</p>
				  <ul id="passwordError">
					  <li> '.$this->config->item('min_password_length', 'ion_auth').' caracteres </li>
					  <li> 1 mayúscula</li>
					  <li> 1 minúscula</li>
					  <li> 1 número</li>  
					  <li> 1 caracter especial</li>  
				  </ul>       
			  </span>');
		  return false;
		} 
   }


}
