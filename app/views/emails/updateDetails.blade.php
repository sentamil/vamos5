<head>

<style>
table, td, th {
    border: 1px solid black;
    background: #f2f2f2;
    padding: 5px;
    font-weight: bolder;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    height: 30px;
}

</style></head>
<h4>Hi, {{ $fname}}!</h4>

<p>The following Vehicle is Updated.</p>

<p>Vehicle Id -  {{$userId}}</p>
<table>
  <tr>
    <th>Field Name</th>
    <th>Old Value</th>
    <th>New Value</th>
</tr>
<tbody>
 @if(isset($oldRef))
@foreach($oldRef as $key => $value)
  <tr>
    <td>{{$key}}</td>
        <td>{{$value}}</td>
    <td>{{$newRef[$key]}}</td>
</tr>
@endforeach
@endif
</tbody>
</table>

<p> Thanks,
 </p>