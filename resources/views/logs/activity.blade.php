@extends('layouts.app')
@section('customcss')
<style type="text/css">
	.panel-body .table tbody td:last-child, .panel-body .table thead th:last-child {
		padding-right: 15px;
		display: table-cell;
	}
</style>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-6 text-left">
							<h1>AOI {{$ip}}</h1>
						</div>
					</div>


				</div>

				<div class="panel-body">
					<table id="table" class="table table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>IP Address</th>
								<th>Machine Name</th>
								<th>Activity Description</th>
								<th>Transaction Date</th>
							</tr>
						</thead>
						<tbody>
							@foreach($logs as $key => $value)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$value->user_ip}}</td>
								<td>{{$value->machine_name}}</td>
								<td>{{$value->activity_name}}</td>
								<td>{{date_time_con($value->date_created)}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('customjs')
<script type="text/javascript">
	$('#table').DataTable();
</script>
@endsection
