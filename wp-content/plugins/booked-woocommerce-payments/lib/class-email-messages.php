<?php

class Booked_WC_Mailing {

	protected $appointment_id = 0;

	protected $appointment_data;
	protected $author_data;

	protected $template_name = '';
	protected $template_path = '';

	protected $subject = '';

	public function __construct() {
		$this->set_subject();
	}

	public function set_template( $template_name='' ) {
		$this->template_name = $template_name;
		$this->template_path = Booked_WC_Fragments::get_path('mail-templates/' . $template_name);

		if ( !$template_name ) {
			$message = __('Mailing template is not specified.', BOOKED_WC_LANGUAGE_PREFIX);
			throw new Exception($message);
		} else if ( !file_exists($this->template_path) ) {
			$message = sprintf(__('The mailing template %1$s does not exist.', BOOKED_WC_LANGUAGE_PREFIX), $template_name);
			throw new Exception($message);
		}

		return $this;
	}

	public function set_appointment( $appointment_id=0 ) {
		$this->appointment_id = (int) $appointment_id;

		if ( !$appointment_id ) {
			$message = __('Appointment ID is not specified', BOOKED_WC_LANGUAGE_PREFIX);
			throw new Exception($message);
		} else if ( !is_integer($appointment_id) ) {
			$message = sprintf(__('Integer expected for $appointment_id, when %1$s given.', BOOKED_WC_LANGUAGE_PREFIX), gettype($appointment_id));
			throw new Exception($message);
		}

		$this->appointment_data = Booked_WC_Appointment::get($this->appointment_id);
		$appointment_author_id = $this->appointment_data->post->post_author;

		$this->author_data = get_user_by('id', $appointment_author_id);

		return $this;
	}

	public function set_subject( $subject='' ) {

		if ( !$subject ) {
			// default subject if not specified
			$subject = __('Booking notification.', BOOKED_WC_LANGUAGE_PREFIX);
		}

		$this->subject = $subject;

		return $this;
	}

	public function send() {
		$admin_email = get_option('admin_email');
		$blog_name = get_bloginfo('name');

		$headers = 'From: ' . $blog_name . ' <' . $admin_email . '>' . "\r\n";
		$subject = $this->subject;

		// build message content
		ob_start();
			include($this->template_path);
		$message = ob_get_clean();

		// set wp_mail content type to text/html
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

		// sent the notification
		return wp_mail($this->author_data->data->user_email, $subject, $message, $headers);
	}
}

class Booked_WC_Mailing_MSG {

	public static function on_appointment_created( $appointment_id ) {
		$mailing = new Booked_WC_Mailing();
		$mailing->set_appointment( (int) $appointment_id );
		$mailing->set_subject(__('Your appointment has been successfully created.', BOOKED_WC_LANGUAGE_PREFIX));
		$mailing->set_template('on-booking-message');
		$mailing->send();
	}

	public static function on_appointment_approval( $appointment_id ) {
		$mailing = new Booked_WC_Mailing();
		$mailing->set_appointment( (int) $appointment_id );
		$mailing->set_subject(__('Your appointment has been approved.', BOOKED_WC_LANGUAGE_PREFIX));
		$mailing->set_template('on-approval-message');
		$mailing->send();
	}
}