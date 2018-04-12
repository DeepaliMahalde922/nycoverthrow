<?php

/**
 * Class MGLInstagramGallery_Admin_PluginStatusRenderer
 * Render PluginStatus checks
 */
class MGLInstagramGallery_Admin_PluginStatusRenderer
{

	/**
	 * Checks rendered content
	 * @var $html
	 */
	public $html;

	/**
	 * Store all checks to render them
	 * @var $checks
	 */
	public $checks;

	public function __construct($checks)
	{
		$this->checks = $checks;
	}

	static public function renderChecks($checks)
	{
		return (new static($checks))->render($checks);
	}

	public function render($checks)
	{
		// Run through check groups
		foreach ($checks as $groupKey => $group) {
			$this->html .= $this->renderGroup($groupKey, $group);
		}

		return $this->html;
	}

	public function renderGroup($key, $group)
	{
		return '
			<div>
				<h3>' . $group['title'] . '</h3>
				<p class="label">' . $group['label'] . '</p>
				' . $this->renderChecksTable($group['checks']) . '
			</div>
		';
	}

	public function renderChecksTable($checks)
	{
		$html = '<table class="wp-list-table widefat fixed striped debug-table">';

		foreach ($checks as $key => $groupConfig) {
			$html .= "<td><strong>" . $groupConfig['title'] . "</strong></td>";

			$valueType = gettype($groupConfig['value']);

			$html .= '<td>';
			$html .= call_user_func(array($this, 'render' . ucfirst($valueType)), $groupConfig['value']);
			$html .= '</td>';

			$html .= '</tr>';
		}

		$this->html .= '</table>';

		return $html;
	}

	public function getGroupByKey($key)
	{
		return $this->checks[$key];
	}

	public function renderBoolean($value)
	{
		if ($value) {
			return '√';
		}

		return 'x';
	}

	public function renderString($value)
	{
		return $value;
	}
}