@extends('includes.vdmheader')
@section('mainContent')

<div id="wrapper">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                 <div class="hpanel">
                    <div class="panel-heading" align="center">
                        <h4><font>Add a school / college / Organization</font></h4>
                    </div>
                    {{ HTML::ul($errors->all()) }}{{ Form::model($organizationId, array('route' => array('vdmOrganization.update', $organizationId), 'method' => 'PUT')) }}
                    <div class="panel-body">
                        <div class="row">
                            <hr>
                            <div class="col-md-1"></div>
                            <div class="col-md-5">
                                <div class="form-group">
                                     {{ Form::label('organizationId', 'School/College/Organization Id :')  }}
                                    {{ Form::text('organizationId', $organizationId, array('class' => 'form-control','disabled' => 'disabled')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('description', 'Description') }}
                                    {{ Form::text('description', $description, array('class' => 'form-control', 'placeholder'=>'Description')) }}

                                </div>
                                <div class="form-group">
                                    {{ Form::label('etc', 'Evening Trip Cron') }}
                                    {{ Form::text('etc', $etc, array('class' => 'form-control', 'placeholder'=>'Evening Trip Cron')) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('parkingAlert', 'Parking Alert') }}
                                    {{ Form::select('parkingAlert',  array( 'no' => 'No','yes' => 'Yes' ), $parkingAlert, array('class' => 'form-control')) }} 
                                </div>

                                <div class="form-group">
                                    {{ Form::label('idleAlert', 'Idle Alert') }}
                                    {{ Form::select('idleAlert',  array( 'no' => 'No','yes' => 'Yes' ), $idleAlert, array('class' => 'form-control')) }} 
                                </div>

                                <div class="form-group">
                                    {{ Form::label('sosAlert', 'SOS Alert') }}
                                    {{ Form::select('sosAlert',  array( 'no' => 'No','yes' => 'Yes' ), $sosAlert, array('class' => 'form-control')) }}           
                                </div>

                                <div class="form-group">
                                    {{ Form::label('overspeedalert', 'Over Speed Alert') }}
                                    {{ Form::select('overspeedalert',  array( 'no' => 'No','yes' => 'Yes' ), $overspeedalert, array('class' => 'form-control')) }} 
                                </div>
                                <div class="form-group">
                                    {{ Form::label('sendGeoFenceSMS', 'Send GeoFence SMS') }}
                                    {{ Form::select('sendGeoFenceSMS',  array( 'no' => 'No','yes' => 'Yes' ), $sendGeoFenceSMS, array('class' => 'form-control','id'=>'sendGeoFenceSMS')) }} 
                                </div>
                                <div class="form-group">
                                    {{ Form::label('address', 'Address') }}
                                    {{ Form::textarea('address', $address, array('class' => 'form-control', 'placeholder'=>'Address')) }}
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    {{ Form::label('mobile', 'Mobile') }}
                                    {{ Form::text('mobile', $mobile, array('class' => 'form-control', 'placeholder'=>'Mobile Number')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('mtc', 'Morning Trip Cron') }}
                                    {{ Form::text('mtc', $mtc, array('class' => 'form-control', 'placeholder'=>'Morning Trip Cron')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('atc', 'AfterNoon Trip Cron') }}
                                    {{ Form::text('atc', $atc, array('class' => 'form-control', 'placeholder'=>'AfterNoon Trip Cron')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('parkDuration', 'Park Duration (Mins)') }}
                                    {{ Form::text('parkDuration', $parkDuration, array('class' => 'form-control', 'placeholder'=>'Park Duration (mins)')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('idleDuration', 'Idle Duration (mins)')}}
                                    {{ Form::text('idleDuration', $idleDuration, array('class' => 'form-control', 'placeholder'=>'Idle Duration (mins)')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('smsSender', 'SMS Sender') }}
                                    {{ Form::text('smsSender', $smsSender, array('class' => 'form-control', 'placeholder'=>'Sms Sender')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('live', 'Show Live Site') }}
                                    {{ Form::select('live',  array( 'no' => 'No','yes' => 'Yes' ), $live, array('class' => 'form-control')) }} 
                                </div>
                                <div class="form-group">
                                    {{ Form::label('smsProvider', 'SMS Provider :') }}
                                    {{ Form::select('smsProvider',  array( $smsP), $smsProvider, array('class' => 'form-control')) }} 
                                </div>
                                <div class="form-group">
                                    {{ Form::label('providerUserName', 'Provider UserName :') }}
                                    {{ Form::text('providerUserName', $providerUserName, array('class' => 'form-control', 'placeholder'=>'Provider Name')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('providerPassword', 'Provider Password :') }}
                                    

                                    {{Form::input('password', 'providerPassword', $providerPassword,array('class' => 'form-control','placeholder'=>'Provider provider Password'))}}

                                </div>
                                <div class="form-group">
                                    {{ Form::label('email', 'Email') }}
                                    {{ Form::text('email', $email, array('class' => 'form-control', 'placeholder'=>'Email')) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('smsPattern', 'SMS Pattern') }}
                                    {{ Form::select('smsPattern',  array( 'nill' => 'Nill','type1' => 'Type 1','type2' => 'Type 2','type3' => 'Type 3' ), $smsPattern, array('class' => 'form-control')) }} 
                                </div>

                               <!--  <div class="form-group">
                                    {{ Form::submit('Update the Vehicle!', array('class' => 'btn btn-primary')) }}
                                </div> -->
                            </div>
                            <br>
                            <div class="col-md-12">
                               <div class="row">
                                    <div class="col-md-12"> 
                                        @foreach($place as $key => $value)
                                            <div class="col-md-4">{{ Form::hidden('oldlatandlan'.$k++, $key, array('class' => 'form-control')) }}</div>
                                            <div class="col-md-4">{{ Form::text('latandlan'.$j++, $key, array('class' => 'form-control')) }}</div>
                                            <div class="col-md-4">{{ Form::text( 'place'.$i++,$value ,array('class' => 'form-control','disabled' => 'disabled'))}}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <br>
                                <div class="row" id="itemsort">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-11">{{ Form::label('startTime', 'Critical Hours') }}</div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-2">{{ Form::label('startTime', 'Start Time : ') }}</div>
                                    <div class="col-md-3">{{  Form::input('time', 'time1', $time1, ['class' => 'form-control', 'placeholder' => 'time'])}}</div>
                                    <div class="col-md-2">{{ Form::label('endTime', 'End Time :') }}</div>
                                    <div class="col-md-3">{{  Form::input('time', 'time2', $time2, ['class' => 'form-control', 'placeholder' => 'time'])}}</div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-2">{{ Form::submit('Update School/College/Organization!', array('class' => 'btn btn-primary')) }}</div>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
                  <hr>
            </div>
        </div>
    </div>
</div>
<div align="center">@stop</div>
 