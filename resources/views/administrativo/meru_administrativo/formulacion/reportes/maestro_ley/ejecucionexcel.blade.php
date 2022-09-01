<table>
	<thead>
		<td>CENTRO COSTO</td>
		<td>PARTIDA</td>
		<td>DESCRIPCION</td>
		<td>MONTO LEY</td>
		<td>MODIFICADO</td>
		<td>PRE-COMP</td>
		<td>COMP</td>
		<td>CAUSADO</td>
		<td>PAGADO</td>
		<td>BLOQUEADO</td>
		<td>DISPONIBLE</td>;
	</thead>
	<tbody>
		@foreach($data as $row)

			<tr>
				<td>{{ $row->cod_cencosto }}</td>
				<td>{{ '4.' . $row->cod_par }}</td>
				<td>{{ $row->des_partida }}</td>
				<td>{{ $row->mto_ley }}</td>
				<td>{{ $row->mto_ley }}</td>
				<td>{{ $row->mto_pre }}</td>
				<td>{{ $row->mto_com }}</td>
				<td>{{ $row->mto_cau }}</td>
				<td>{{ $row->mto_pag }}</td>
				<td>{{ $row->mto_blo ?? 0 }}</td>
				<td>{{ $row->mto_dis }}</td>
			</tr>

		@endforeach
	</tbody>
</table>