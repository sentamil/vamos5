@extends('includes.vdmheader')
@section('mainContent')

<div id="wrapper">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading" align="center">
                       <h4><b>Select the Dealer name</b></h4>
                    </div>
                    <div class="panel-body">
                        <hr>
                        {{ HTML::ul($errors->all()) }}{{ Form::open(array('url' => 'vdmVehicles/findDealerList')) }}
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-3">{{ Form::label('dealerId', 'Dealers Id') }}</div>
                            <div class="col-md-3">{{ Form::select('dealerId', array($dealerId), Input::old(''),array('class'=>'form-control')) }}</div>
                        </div>
                        <br>
                        <br>
                        <div class="navbar"></div>
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-2">{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</div>
                            
                        </div>
                        <br>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="top: 0; left: 0;right: 0; padding-top: 150px;" align="center">
    <hr>
    @stop</div>




    {{ Form::close() }}

@stop