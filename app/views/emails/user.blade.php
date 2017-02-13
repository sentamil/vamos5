<head>

<style>
table, td, th {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    height: 30px;
}

.caption {
  background: #f2f2f2;
  padding: 5px;
  font-weight: bolder;
}

</style></head>
<h4>Hi, {{ $username}}!</h4>

<p>The following {{$cap}} is Updated.</p>

<p>{{$cap}} -  {{$groupName}}</p>
<table>
  <tr>
    
    <td colspan="2" class="caption">Old Value</td>
    
</tr>
<tbody>
@if(isset($oldVehi))
@foreach($oldVehi as $key => $value)
  <tr>
    <td>{{$key}}</td>
    <td>{{$value}}</td>
</tr>
@endforeach
@endif

<tr>
    
    <td colspan="2" class="caption">Updated Value</td>
    
</tr>
@if(isset($newVehi))
@foreach($newVehi as $key => $value)
  <tr>
    <td>{{$key}}</td>
    <td>{{$value}}</td>
</tr>
@endforeach
@endif
</tbody>
</table>

<p> Thanks,
 </p>