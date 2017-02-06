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
<h4>Hi, {{ $url}}!</h4>

<p>The following group is Franchises.</p>

<p>Franchises Name -  {{$url}}</p>
<table>
  <tr>
    
    <td colspan="2" class="caption">Old Details</td>
    
</tr>
<tbody>
@if(isset($old))
@foreach($old as $key => $value)
  <tr>
    <td>{{$key}}</td>
    <td>{{$value}}</td>
</tr>
@endforeach
@endif

<tr>
    
    <td colspan="2" class="caption">Updated Details</td>
    
</tr>
@if(isset($new))
@foreach($new as $key => $value)
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