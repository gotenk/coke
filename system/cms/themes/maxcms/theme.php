<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Theme_Maxcms extends Theme {

    public $name			= 'MaxCMS - Admin Theme';
    public $author			= 'MaxCMS Dev Team';
    public $author_website	= 'http://maxsolution.co.id/';
    public $website			= 'http://maxsolution.co.id/';
    public $description		= 'MaxCMS admin theme. HTML5 and CSS3 styling.';
    public $version			= '1.0.0';
	public $type			= 'admin';
	public $options 		= array(							
									'maxcms_analytics_graph' => 	array('title' => 'Analytics Graph',
																'description'   => 'Would you like to display the graph on the dashboard?',
																'default'       => 'yes',
																'type'          => 'radio',
																'options'       => 'yes=Yes|no=No',
																'is_required'   => true),
								   );
	
	/**
	 * Run() is triggered when the theme is loaded for use
	 *
	 * This should contain the main logic for the theme.
	 *
	 * @access	public
	 * @return	void
	 */
	public function run()
	{
		// only load these items on the dashboard
		if ($this->module == '' && $this->method != 'login' && $this->method != 'help')
		{
			// don't bother fetching the data if it's turned off in the theme
			if ($this->theme_options->maxcms_analytics_graph == 'yes')		self::get_analytics();
			
		}
	}
	
	public function get_analytics()
	{
		if ($this->settings->ga_email and $this->settings->ga_password and $this->settings->ga_profile)
		{
			// Not false? Return it
			if ($cached_response = $this->maxcache->get('analytics'))
			{
				$data['analytic_visits'] = $cached_response['analytic_visits'];
				$data['analytic_views'] = $cached_response['analytic_views'];
			}

			else
			{
				try
				{
					$this->load->library('analytics', array(
						'username' => $this->settings->ga_email,
						'password' => $this->settings->ga_password
					));

					// Set by GA Profile ID if provided, else try and use the current domain
					$this->analytics->setProfileById('ga:'.$this->settings->ga_profile);

					$end_date = date('Y-m-d');
					$start_date = date('Y-m-d', strtotime('-1 month'));

					$this->analytics->setDateRange($start_date, $end_date);

					$visits = $this->analytics->getVisitors();
					$views = $this->analytics->getPageviews();

					/* build tables */
					if (count($visits))
					{
						foreach ($visits as $date => $visit)
						{
							$year = substr($date, 0, 4);
							$month = substr($date, 4, 2);
							$day = substr($date, 6, 2);

							$utc = mktime(date('h') + 1, null, null, $month, $day, $year) * 1000;

							$flot_datas_visits[] = '[' . $utc . ',' . $visit . ']';
							$flot_datas_views[] = '[' . $utc . ',' . $views[$date] . ']';
						}

						$flot_data_visits = '[' . implode(',', $flot_datas_visits) . ']';
						$flot_data_views = '[' . implode(',', $flot_datas_views) . ']';
					}

					$data['analytic_visits'] = $flot_data_visits;
					$data['analytic_views'] = $flot_data_views;

					// Call the model or library with the method provided and the same arguments
					$this->maxcache->write(array('analytic_visits' => $flot_data_visits, 'analytic_views' => $flot_data_views), 'analytics', 60 * 60 * 6); // 6 hours
				}

				catch (Exception $e)
				{
					$data['messages']['notice'] = sprintf(lang('cp:google_analytics_no_connect'), anchor(ADMIN_URL.'/settings', lang('cp:nav_settings')));
				}
			}

			// make it available in the theme
			$this->template->set($data);
		}
	}
	
	}

/* End of file theme.php */