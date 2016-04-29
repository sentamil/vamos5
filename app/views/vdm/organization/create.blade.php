@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="hpanel">
			<h4><font><b>Add a school / college / Organization</b></font></h4>
			<div class="panel-body">
				{{ HTML::ul($errors->all()) }}{{ Form::open(array('url' => 'vdmOrganization')) }}
				<div class="col-md-12">
					<span id="validation" style="color: red; font-weight: bold"></span>
				</div>
				<div class="row">
					<div class="col-md-3">{{ Form::label('organizationId', 'School/College/Organization Id * :')  }}</div>
					<div class="col-md-6">{{ Form::text('organizationId', Input::old('organizationId'), array('class' => 'form-control', 'required' => 'required', 'placeholder'=>'School/College/Organization Id', 'id'=>'orgId')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('description', 'Description :') }}</div>
					<div class="col-md-6">{{ Form::text('description', Input::old('[description'), array('class' => 'form-control', 'placeholder'=>'Description')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('email', 'Email * :') }}</div>
					<div class="col-md-6">{{ Form::Email('email', Input::old('email'), array('class' => 'form-control', 'required' => 'required','placeholder'=>'Email')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('mobile', 'Mobile * :') }}</div>
					<div class="col-md-6">{{ Form::Number('mobile', Input::old('mobile'), array('class' => 'form-control', 'required' => 'required','placeholder'=>'Mobile Number')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('mtc', 'Morning Trip Cron :') }}</div>
					<div class="col-md-6">{{ Form::text('mtc', Input::old('mtc'), array('class' => 'form-control','placeholder'=>'Morning Trip Cron')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('atc', 'After Trip Cron :') }}</div>
					<div class="col-md-6">{{ Form::text('atc', Input::old('atc'), array('class' => 'form-control','placeholder'=>'After Trip Cron')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('etc', 'Evening Trip Cron :') }}</div>
					<div class="col-md-6">{{ Form::text('etc', Input::old('etc'), array('class' => 'form-control','placeholder'=>'Evening Trip Cron')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('smsProvider', 'SMS Provider') }}</div>
					<div class="col-md-6">{{ Form::select('smsProvider',  array( $smsP), Input::old('smsProvider'), array('class' => 'form-control','placeholder'=>'SMS Provider')) }}</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-md-3">{{ Form::label('providerUserName', 'SMS Provider User Name') }}</div>
					<div class="col-md-6">{{ Form::text('providerUserName', Input::old('providerUserName'), array('class' => 'form-control','placeholder'=>'SMS Provider Name')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('providerPassword', 'SMS Provider Password') }}</div>
					<div class="col-md-6">{{ Form::text('providerPassword', Input::old('providerPassword'), array('class' => 'form-control','placeholder'=>'SMS Provider Password')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('parkingAlert', 'Parking Alert :') }}</div>
					<div class="col-md-6">{{ Form::select('parkingAlert',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('parkingAlert'), array('class' => 'form-control')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('idleAlert', 'Idle Alert :') }}</div>
					<div class="col-md-6">{{ Form::select('idleAlert',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('idleAlert'), array('class' => 'form-control')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('sosAlert', 'SOS Alert :') }}</div>
					<div class="col-md-6"> {{ Form::select('sosAlert',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('sosAlert'), array('class' => 'form-control')) }} </div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('overspeedalert', 'Over Speed Alert :') }}</div>
					<div class="col-md-6">{{ Form::select('overspeedalert',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('overspeedalert'), array('class' => 'form-control')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('sendGeoFenceSMS', 'Send GeoFence SMS :') }}</div>
					<div class="col-md-6">{{ Form::select('sendGeoFenceSMS',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('sendGeoFenceSMS'), array('class' => 'form-control')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('parkDuration', 'Park Duration (mins) :') }}</div>
					<div class="col-md-6">{{ Form::text('parkDuration', Input::old('parkDuration'), array('class' => 'form-control','placeholder'=>'Park Duration (mins)')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('idleDuration', 'Idle Duration (mins) :') }}</div>
					<div class="col-md-6">{{ Form::text('idleDuration', Input::old('idleDuration'), array('class' => 'form-control','placeholder'=>'Idle Duration (mins)')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('smsSender', 'Sms Sender') }}</div>
					<div class="col-md-6">{{ Form::text('smsSender', Input::old('smsSender'), array('class' => 'form-control','placeholder'=>'Sms Sender')) }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('live', 'Show Live Site') }}</div>
					<div class="col-md-6">{{ Form::select('live',  array( 'no' => 'No','yes' => 'Yes' ), Input::old('live'), array('class' => 'form-control')) }} </div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-3">{{ Form::label('address', 'Address :') }}</div>
					<div class="col-md-6"><textarea rows="4" cols="40" class="form-control" placeholder="Address"></textarea></div>
				</div>
				<br />
				<ul id="itemsort">
					<div class="row">
						<div class="col-md-2">{{ Form::label('startTime', 'Start Time :') }}</div>
						<div class="col-md-4">{{  Form::input('time', 'time1', null, ['class' => 'form-control', 'placeholder' => 'time'])}}</div>
						<div class="col-md-2">{{ Form::label('endTime :', 'End Time :') }}</div>
						<div class="col-md-4">{{  Form::input('time', 'time2', null, ['class' => 'form-control', 'placeholder' => 'time'])}}</div>
					</div> 
				</ul>
				<br />
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">{{ Form::submit('Add the School/College/Organization!', array('class' => 'btn btn-primary')) }}</div>
				</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>	
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<script type="text/javascript">
	$('#orgId').on('change', function() {
		$('#validation').text('');
		var postValue = {
			'id': $(this).val()

			};
		// alert($('#groupName').val());
		$.post('{{ route("ajax.ordIdCheck") }}',postValue)
			.done(function(data) {
				
				$('#validation').text(data);
        		console.log("Sucess-------->"+data);
        		
      		}).fail(function() {
        		console.log("fail");
      });

		
	})

</script>
@include('includes.js_create')
</body>
</html>