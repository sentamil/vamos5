@extends('includes.vdmheader')
@section('mainContent')


<h1>Showing UserId</h1>

    <div class="jumbotron text-center">
        <h2>{{ $poiName }}</h2>
            <p>
                <strong><br/>Mobile No: </strong><br/><br/> {{ $mobileNos }}<br>
        </p>
   
    </div>
@stop