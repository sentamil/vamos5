@extends('includes.reportsheader')
@section('mainContent')


<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<td>Vehicle Id</td>
			<td>Position</td>
			<td>Status</td>
			<td>Address</td>
			<td>GeoLocation</td>
			<td>LastSeen</td>

		</tr>
	</thead>
	<tbody>
	@foreach($vehicleLocations as $key => $value)
	
		<tr>
			<td>{{ $value->vehicleId }}</td>
			<td>{{ $value->position}}</td>	
			<td>{{  $value->status}}</td>
			<td><?php  	$lat = $value->latitude;
		$lng = $value->longitude;

		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true";
		$data = @file_get_contents($url);
		$jsondata = json_decode($data,true);
		if(is_array($jsondata) && $jsondata['status'] == "OK")
		{
			$city = $jsondata['results']['0']['address_components']['2']['long_name'];
			$country = $jsondata['results']['0']['address_components']['5']['long_name'];
			$street = $jsondata['results']['0']['address_components']['1']['long_name'];
		}
		$address = $street . ','.$city . ',' . $country; 
		echo $address;?></td>
			<td>{{  $value->latitude }},{{$value->longitude}}</td>
			<td>{{ 'Now'}}</td>
		</tr>
	@endforeach
	</tbody>

</table>
{{link_to_asset('download', 'file download', $attributes = array(), $secure = null); }}
 
   <a href="/download" class="btn btn-large pull-right"><i class="icon-download-alt"> </i> Download Brochure </a>
 



@stop
