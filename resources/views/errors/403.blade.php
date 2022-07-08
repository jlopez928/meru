@extends('layouts.app')

@section('contenido')

	<div class="container mt-5">
		<div class="row">
			<div class="error-page">
				<h2 class="headline text-warning">403</h2>
				<div class="error-content">
					<h3><i class="fas fa-exclamation-triangle text-warning"></i> Acción no Autorizada</h3>
					<p>
						Esta acción NO está autorizada.
					</p>
				</div>
			</div>
		</div>
	</div>

@endsection