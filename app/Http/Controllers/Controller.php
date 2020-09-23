<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $_status_code;
	
    public function __construct() {
		$this->_status_code = new \stdclass();
		$this->_status_code->VALIDATED = 202;
		$this->_status_code->SAVED = 201;				
		$this->_status_code->DELETED = 204;		
		$this->_status_code->ERROR = 400;		
    }
	
	protected function errorMessage($messageException) {
		Log::warning($messageException);
		return  __('text.error_message');
	}	
}
