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

</style></head>
<h4>Hi, {{ $username}}!</h4>

<p>The following group is Updated.</p>

<p>Group Name -  {{$groupName}}</p>
<table>
  <tr>
    
    <td colspan="2">Old Vehicles</td>
    
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
    
    <td colspan="2">Updated Vehicles</td>
    
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