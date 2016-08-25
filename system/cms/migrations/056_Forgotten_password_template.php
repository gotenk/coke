<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Forgotten_password_template extends CI_Migration {

	public function up()
	{
		$this->db->delete('email_templates', array('slug' => 'forgotten_password'));
		$this->db->delete('email_templates', array('slug' => 'new_password'));
		
		$this->db->insert('email_templates', array(
			'slug'				=> 'forgotten_password',
			'name'				=> 'Forgotten Password Email',
			'description' 		=> 'The email that contains a password reset code',
			'subject'			=> '{max:settings:site_name} - Forgotten Password',
			'body'				=> '<p>Hello {max:user:first_name},</p>
									<p>It seems you have requested a password reset. Please click this link to complete the reset: <a href="{max:url:site}users/reset_pass/{max:user:forgotten_password_code}">{max:url:site}users/reset_pass/{max:user:forgotten_password_code}</a></p>
									<p>If you did not request a password reset please disregard this message. No further action is necessary.</p>',
			'lang'				=> 'en',
			'is_default'		=> 1,
		));
		
		$this->db->insert('email_templates', array(
			'slug'				=> 'new_password',
			'name'				=> 'New Password Email',
			'description' 		=> 'After a password is reset this email is sent containing the new password',
			'subject'			=> '{max:settings:site_name} - New Password',
			'body'				=> '<p>Hello {max:user:first_name},</p>
									<p>Your new password is: {max:new_password}</p>
									<p>After logging in you may change your password by visiting <a href="{max:url:site}edit-profile">{max:url:site}edit-profile</a></p>',
			'lang'				=> 'en',
			'is_default'		=> 1,
		));
	}

	public function down()
	{
		$this->db->delete('email_templates', array('slug' => 'forgotten_password'));
		$this->db->delete('email_templates', array('slug' => 'new_password'));
	}
}