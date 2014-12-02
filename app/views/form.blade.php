<html lang="en">
 <head>
 <meta charset="UTF-8">
  <title>Laravel Form Validation!</title>

        <!-- load bootstrap -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <style>
                body    { padding-bottom:40px; padding-top:40px; }
        </style>

 </head>
 <body>

{{ Form::open(array('url'=>'form-submit')) }}
  
  <!-- text input field -->
  {{ Form::label('username','Username',array('id'=>'','class'=>'')) }}
  {{ Form::text('username','clivern',array('id'=>'','class'=>'')) }}

  <!-- select box -->
<?php $arr = array('enabled'=>'Enabled','disabled'=>'Disabled')?>

<div

{{ Form::label('status','Status',array('id'=>'','class'=>'')) }}
{{ Form::select('status',$arr,'enabled') }}

</div>

  
  <!-- submit buttons -->
  {{ Form::submit('Save') }}
  
  <!-- reset buttons -->
  {{ Form::reset('Reset') }}
  
  
  {{ Form::close() }}
 </body>
</html>
