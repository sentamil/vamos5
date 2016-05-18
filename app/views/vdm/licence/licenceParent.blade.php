<div id="wrapper">
	<div class="content animate-panel">
		<div class="hpanel">
       		<h6><b><font color="red"> {{ HTML::ul($errors->all()) }}</font></b></h6>
           	<div class="panel-heading">
             	<h4><b><font>Licences</font></b></h4>
            </div>
            <div class="panel-body">
            	{{ Form::open(array('url' => 'Licence')) }}
            	<div class="row">
            		<div class="col-md-6">
            			{{ Form::label('month', 'Month:') }}
						{{ Form::select('month', array($month),$monthT , array('class' => 'form-control')) }}
            		</div>
            		<div class="col-md-6">
            			{{ Form::label('Year', 'year:') }}
						{{ Form::select('year', array($year),$yearT , array('class' => 'form-control')) }}
            		</div>
            	</div>
            	<br>
            	<div class="row">
            		<div class="col-md-6">
            			{{ Form::label('Licence', 'Licence:') }}
						{{ Form::select('Licence', array($Licence), $typeT, array('class' => 'form-control')) }}
            		</div>
            		<div class="col-md-6">
            			{{ Form::label('own', 'Ownership:') }}
						{{ Form::select('own', array($own), $ownT, array('class' => 'form-control')) }}
            		</div>
            	</div>
            	<br>
            	<div class="row">
            		<div class="col-md-9"></div>
            		<div class="col-md-3">{{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}</div>
            	</div>
            	{{ Form::close() }}
            </div>
		</div>
	</div>
</div>

 