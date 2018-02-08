<?php
class VdmDealersScanController extends \BaseController {
	

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function dealerSearch()
    {
        log::info(' reach the road speed function ');
        $orgLis = [];
            return View::make('vdm.dealers.index1')->with('dealerlist', $orgLis);
    }   
    public function dealerScan() {
       if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        Log::info('username:' . $username . '  :: fcode' . $fcode);
        $redisDealerCacheID = 'S_Dealers_' . $fcode;
        $dealerlist = $redis->smembers ( $redisDealerCacheID);
        $text_word = Input::get('text_word');
        $cou = $redis->SCARD($redisDealerCacheID); // log::info($cou);
        $orgLi = $redis->sScan( $redisDealerCacheID, 0, 'count', $cou, 'match', '*'.$text_word.'*'); //log::info($orgLi);
        $orgL = $orgLi[1];
        $userGroups = null;
        $userGroupsArr = null;
		$dealerWeb=null;
        foreach ( $orgL as $key => $value ) { 
            $userGroups = $redis->smembers ( $value);
            $userGroups = implode ( '<br/>', $userGroups );
            $detailJson=$redis->hget ( 'H_DealerDetails_' . $fcode, $value);
            $detail=json_decode($detailJson,true);
            $userGroupsArr = array_add ( $userGroupsArr, $value, $detail['mobileNo'] );
			$dealerWeb = array_add ( $dealerWeb, $value, $detail['website'] );
        }
        return View::make ( 'vdm.dealers.index1' )->with ( 'fcode', $fcode )->with ( 'userGroupsArr', $userGroupsArr )->with ( 'dealerlist', $orgL )->with ( 'dealerWeb', $dealerWeb );
    }
	public function dealerScanNew($id) {
       if (! Auth::check ()) {
            return Redirect::to ( 'login' );
        }
        $username = Auth::user ()->username;
        $redis = Redis::connection ();
        $fcode = $redis->hget ( 'H_UserId_Cust_Map', $username . ':fcode' );
        Log::info('username:' . $username . '  :: fcode' . $fcode);
        $redisDealerCacheID = 'S_Dealers_' . $fcode;
        $dealerlist = $redis->smembers ( $redisDealerCacheID);
        $text_word = $id;
        $cou = $redis->SCARD($redisDealerCacheID); // log::info($cou);
        $orgLi = $redis->sScan( $redisDealerCacheID, 0, 'count', $cou, 'match','*'.$text_word.'*'); //log::info($orgLi);
        $orgL = $orgLi[1];
        $userGroups = null;
        $userGroupsArr = null;
		$dealerWeb=null;
        foreach ( $orgL as $key => $value ) { 
            $userGroups = $redis->smembers ( $value);
            $userGroups = implode ( '<br/>', $userGroups );
            $detailJson=$redis->hget ( 'H_DealerDetails_' . $fcode, $value);
            $detail=json_decode($detailJson,true);
            $userGroupsArr = array_add ( $userGroupsArr, $value, $detail['mobileNo'] );
			$dealerWeb = array_add ( $dealerWeb, $value, $detail['website'] );
        }
        return View::make ( 'vdm.dealers.index1' )->with ( 'fcode', $fcode )->with ( 'userGroupsArr', $userGroupsArr )->with ( 'dealerlist', $orgL )->with ( 'dealerWeb', $dealerWeb );
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
	*/
}