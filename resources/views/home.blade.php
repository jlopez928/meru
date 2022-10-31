@extends('layouts.aplicacion')

@section('content')		  	

	<div class="container-fluid mt-2">
		<img src="{{ asset('img/home.png') }}" style="height:600px; width:1400px;">
	</div>
@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection