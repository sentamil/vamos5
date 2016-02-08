<?php
class VdmVehicleViewController extends \BaseController {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		
		$user = new VdmVehicleController;
		$page=$user->index();
		Session::put('vCol',2);
		return $page;
	}
	
	
	
        
    
}
