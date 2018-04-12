<?php

/**
 * Class PluginStatus
 *
 */
class MGLInstagramGallery_Admin_PluginStatus
{

	/**
	 * Store all the checked compatibilities and its results
	 * @var array
	 */
	private $checks = array();

	/**
	 * Helper method to return an array with all checked compatibilities
	 * @return array
	 */
	static public function getPluginStatus()
	{
		return (new static)->getChecks();
	}

	/**
	 * Run all compatibilty checks and return an array with al the compatibility check results
	 *
	 * @return array
	 */
	protected function getChecks()
	{
		$this
			->checkServerDetails()
			->checkMBFunctionsCompatibility()
			->checkEmojisCompatibility()
			->checkWPSettings();

		return $this->checks;
	}

	protected function checkMBFunctionsCompatibility()
	{
		$this->checks['mb-functions'] = array(
			'title' => 'MB Functions',
			'label' => 'Compatibility with multibyte functions. Make possible translate non latin characters.',
			'checks' => array(
				'mb_decode_numericentity' => array(
					'title'	=> 'mb_decode_numericentity',
					'value'	=> function_exists('mb_decode_numericentity')
				),
				'mb_strlen' => array(
					'title'	=> 'mb_strlen',
					'value'	=> function_exists('mb_strlen')
				),
				'mb_substr' => array(
					'title'	=> 'mb_substr',
					'value'	=> function_exists('mb_substr')
				),
				'mb_detect_encoding' => array(
					'title'	=> 'mb_detect_encoding',
					'value'	=> function_exists('mb_detect_encoding'),
				)
			)
		);
		
		return $this;
	}

	protected function checkServerDetails()
	{
		$this->checks['server-details'] = array(
			'title' => 'Server details',
			'label' => 'Check server characteristics',
			'checks' => array(
				'php-version'	=> array(
					'title' => 'PHP Version',
					'value' => phpversion()
				),
			)
		);

		return $this;
	}

	protected function checkEmojisCompatibility()
	{
		$this->checks['emojis'] = array(
			'title' => 'Emojis compatibility',
			'label' => 'Allow show emojis on Instagram photos descriptions',
			'checks' => array(
				'wp_encode_emoji'	=> array(
					'title' => 'wp_encode_emoji',
					'value' => function_exists('wp_encode_emoji')
				),
			)
		);

		return $this;
	}
	
	protected function checkWPSettings()
	{
		$this->checks['wp-settings'] = array(
			'title' => 'WP Settings',
			'label' => 'Check WP Status',
			'checks' => array(
				'wp-debug'	=> array(
					'title' => 'WP Debug enabled',
					'value' => WP_DEBUG
				),
			)
		);
	}
}