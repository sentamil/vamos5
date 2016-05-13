@include('includes.header_index')
@include('vdm.licence.licenceParent')


<div class="col-sm-12">

<div class="col-md-5">
								<div class="form-group">
									{{ Form::label('preMonthly', 'Presents Month :')  }}
									<a  >{{$preMonthly}}</a>
								</div>

								<div class="form-group">
									
									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'1'.';'.$typeT.';'.$ownT) }}">Monthly :</a>
									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'1'.';'.$typeT.';'.$ownT) }}">{{$monthly}}</a>
								</div>
								<div class="form-group">
									{{ Form::label('perQuater', 'present Quaterly :')  }}
									<a  href="{{ URL::to('vdmVehicles/calibrateOil/') }}">{{$perQuater}}</a>



								</div>
								<div class="form-group">
									

									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'2'.';'.$typeT.';'.$ownT) }}">Quaterly :</a>
									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'2'.';'.$typeT.';'.$ownT) }}">{{$quaterly}}</a>
								</div>
								<div class="form-group">
									{{ Form::label('perHalfyearfly', 'Present Halfyearfly :')  }}
									<a  href="{{ URL::to('vdmVehicles/calibrateOil/') }}">{{$perHalfyearfly}}</a>


									
								</div>

								<div class="form-group">
									



									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'3'.';'.$typeT.';'.$ownT) }}">Halfyearfly :</a>
									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'3'.';'.$typeT.';'.$ownT) }}">{{$halfyearfly}}</a>
								</div>
								<div class="form-group">
									

								<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'4'.';'.$typeT.';'.$ownT) }}">Yearly :</a>
									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'4'.';'.$typeT.';'.$ownT) }}">{{$yearfly}}</a>


								</div>
								<div class="form-group">
									
								<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'5'.';'.$typeT.';'.$ownT) }}">2 years :</a>
									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'5'.';'.$typeT.';'.$ownT) }}">{{$yearfly2}}</a>


								</div>
								<div class="form-group">
									

									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'6'.';'.$typeT.';'.$ownT) }}">3 years :</a>
									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'6'.';'.$typeT.';'.$ownT) }}">{{$yearfly3}}</a>
								</div>
								<div class="form-group">
									
									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'7'.';'.$typeT.';'.$ownT) }}">4 years :</a>
									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'7'.';'.$typeT.';'.$ownT) }}">{{$yearfly4}}</a>
								</div>
								<div class="form-group">
								<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'8'.';'.$typeT.';'.$ownT) }}">5 years :</a>
									<a href="{{ URL::to('Licence/ViewDevices/'.$monthT.';'.$yearT.';'.'8'.';'.$typeT.';'.$ownT) }}">{{$yearfly5}}</a>



								</div>
								</div>
</div>


@include('includes.js_index')

</body>
</html>