<?php

namespace Waze\Controllers;

use Waze\Controller;

class Settings extends Controller
{
	public function process()
	{
		$this->layout([
			'js' => [
				'https://code.jquery.com/jquery-2.2.4.min.js' => 'integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"',
				'/builds/settings.js' => '',
			],
		]);
		return $this->render('settings', array());
	}
}
