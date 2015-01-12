<?php

class PlayBackController  extends \BaseController {

    public function replay()
	{
		
		if (! Auth::check ()) {
			return Redirect::to ( 'login' );
		}
		
		return View::make('replay');
	}

}