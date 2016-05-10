@include('includes.header_create')
<!-- Main Wrapper -->
<div id="wrapper">
	<div class="content animate-panel">
		<div class="row">
    		<div class="col-lg-12">
        		<div class="hpanel">
               		 <div class="panel-heading">
                  		<h4><b><font>Dealers Create</font></b></h6> 
                	</div>
               		 <div class="panel-body">
                		{{ HTML::ul($errors->all()) }}
						{{ Form::open(array('url' => 'vdmDealers', 'files' => true, 'enctype'=>'multipart/form-data')) }}
					<div class="col-md-12">
							<span id="validation" style="color: red; font-weight: bold"></span>
						</div>
                	<div class="row">	
                		<div class="col-sm-9">
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('dealerId', 'Dealer ID') }}
								</div>
								<div class="col-md-6">
									{{ Form::text('dealerId', Input::old('dealerId'), array('class' => 'form-control',  'required' => 'required', 'placeholder'=>'Dealer Id', 'id'=>'dealerName')) }}
								</div>
							</div>
							<br />
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('mobileNo', 'Mobile Number') }}									
								</div>
								<div class="col-md-6">
									{{ Form::text('mobileNo', Input::old('mobileNo'), array('class' => 'form-control', 'required' => 'required', 'placeholder'=>'Mobile Number')) }}
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3">
									{{ Form::label('email', 'Email') }}	
								</div>
								<div class="col-md-6">
									{{ Form::email('email', Input::old('email'), array('class' => 'form-control', 'required' => 'required', 'placeholder'=>'E-mail Id')) }}
								</div>
							</div>
							<br />
							 <div class="row">
								<div class="col-md-3">
									{{ Form::label('password', 'password') }}	
								</div>
								<div class="col-md-6">
									{{ Form::text('password', Input::old('password'), array('class' => 'form-control','required' => 'required','placeholder'=>'Password')) }}
								</div>
							</div>
							  <br />
							 <div class="row">
								<div class="col-md-3">
									{{ Form::label('website', 'website') }}	
								</div>
								<div class="col-md-6">
									{{ Form::text('website', Input::old('website'), array('class' => 'form-control','placeholder'=>'Website', 'required' => 'required')) }}
								</div>
								
							</div>


 							<br />
								<div class="row">
								<div class="col-md-3">
							{{ Form::label('smsSender', 'SMS Sender') }}
							</div>
							<div class="col-md-6">
							{{ Form::text('smsSender', Input::old('smsSender'), array('class' => 'form-control', 'placeholder'=>'SMS Sender')) }}
								</div>
						</div> <br />
						<div class="row">
								<div class="col-md-3">
		{{ Form::label('smsProvider', 'SMS Provider') }}
			</div><div class="col-md-6">
		 		{{ Form::select('smsProvider',  array( $smsP), Input::old('smsProvider'), array('class' => 'form-control')) }} 
			</div>
			</div> <br />
							<div class="row">
								<div class="col-md-3">
		{{ Form::label('providerUserName', 'SMS Provider User Name') }}
		</div><div class="col-md-6">
		{{ Form::text('providerUserName', Input::old('providerUserName'), array('class' => 'form-control', 'placeholder'=>'SMS Provider Name')) }}
		</div>
	</div> <br />
	<div class="row">
								<div class="col-md-3">
		{{ Form::label('providerPassword', 'SMS Provider Password') }}</div>
		<div class="col-md-6">
		{{ Form::text('providerPassword', Input::old('providerPassword'), array('class' => 'form-control', 'placeholder'=>'SMS Provider Password')) }}
		</div>
		
	</div>
	<br>
	<div class="row">
		<div class="col-md-3">{{Form::label('Image 52*52(png)')}} {{ Form::file('logo_small', array('class' => 'form-control')) }}</div>
		<div class="col-md-3">{{Form::label('Image 272*144(png)')}}{{ Form::file('logo_mob', array('class' => 'form-control')) }}</div>
		<div class="col-md-3">{{Form::label('Image 144*144(png)')}}{{ Form::file('logo_desk', array('class' => 'form-control')) }}</div>
		
	</div>
	<br>
	<div class="row">
		<div class="col-md-6"></div>
		<div class="col-md-3">{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}{{ Form::close() }}</div>
	</div>

							
						</div>
            	</div>
			</div>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

<script type="text/javascript">
	$('#dealerName').on('change', function() {
		$('#validation').text('');
		var postValue = {
			'id': $(this).val()

			};
		// alert($('#groupName').val());
		$.post('{{ route("ajax.dealerCheck") }}',postValue)
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