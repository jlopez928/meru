@php
	$xctro = ''; // Control de Cambio de Centro de Costo
	$xgen  = ''; // Control de Cambio de Partida generica
	$t1 = 0; $t2 = 0; $t3 = 0; $t4 = 0; $t5 = 0; $t6 = 0; $t7 = 0;  // Totales Generales
	$pt1 = 0; $pt2 = 0; $pt3 = 0; $pt4 = 0; $pt5 = 0; $pt6 = 0; $pt7 = 0; // Totales por partida
	$ct1 = 0; $ct2 = 0; $ct3 = 0; $ct4 = 0; $ct5 = 0; $ct6 = 0; $ct7 = 0; // Totales por centro de Costo
@endphp

<table>
	<tbody>
		<tr>
			<td style="text-align:center;font-weight:bold;border:1px solid #000000;" colspan="11">
				RESUMEN PRESUPUESTARIO
			</td>
		</tr>
		<tr>
			<td style="text-align:center;font-weight:bold;border:1px solid #000000;" colspan="11">
				MES: {{ $mes }} - AÑO: {{ $anoPro }}
			</td>
		</tr>
		<tr>
			<td style="text-align:center;font-weight:bold;border:1px solid #000000;" colspan="11"></td>
		</tr>
		<tr>
			<td style="width:150px;text-align:center;background-color:#808080;color:#FFFFFF;font-weight:bold;border:1px solid #000000;"><b>CENTRO COSTO</b></td>
			<td style="text-align:center;background-color:#808080;color:#FFFFFF;font-weight:bold;border:1px solid #000000;"><b>PARTIDA</b></td>
			<td style="text-align:center;background-color:#808080;color:#FFFFFF;font-weight:bold;border:1px solid #000000;"><b>DESCRIPCIÓN</b></td>
			<td style="width:150px;text-align:center;background-color:#808080;color:#FFFFFF;font-weight:bold;border:1px solid #000000;"><b>MONTO LEY</b></td>
			<td style="width:150px;text-align:center;background-color:#808080;color:#FFFFFF;font-weight:bold;border:1px solid #000000;"><b>MODIFICADO</b></td>
			<td style="width:150px;text-align:center;background-color:#808080;color:#FFFFFF;font-weight:bold;border:1px solid #000000;"><b>PRE-COMP</b></td>
			<td style="width:150px;text-align:center;background-color:#808080;color:#FFFFFF;font-weight:bold;border:1px solid #000000;"><b>COMP</b></td>
			<td style="width:150px;text-align:center;background-color:#808080;color:#FFFFFF;font-weight:bold;border:1px solid #000000;"><b>CAUASDO</b></td>
			<td style="width:150px;text-align:center;background-color:#808080;color:#FFFFFF;font-weight:bold;border:1px solid #000000;"><b>PAGADO</b></td>
			<td style="width:150px;text-align:center;background-color:#808080;color:#FFFFFF;font-weight:bold;border:1px solid #000000;"><b>BLOQUEADO</b></td>
			<td style="width:150px;text-align:center;background-color:#808080;color:#FFFFFF;font-weight:bold;border:1px solid #000000;"><b>DISPONIBLE</b></td>
		</tr>

		@foreach($data as $row)

			@php
				$gen = '4.' . \Str::padLeft($row->cod_par, 2, '0');
			@endphp

			{{-- Primera iteración --}}
			@if ($xctro == '')

				@php
					$xctro = $row->cod_cencosto; // Guardar Centro de Costo
					$xgen  = $gen;
				@endphp

				{{-- Nombre Centro --}}
				<tr>
					<td style="text-align:center;border:1px solid #000000;">{{ $row->cod_cencosto }}</td>
					<td style="border:1px solid #000000;">{{ $row->des_centro }}</td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
				</tr>

				{{-- Partida General --}}
				<tr>
					<td style="border:1px solid #000000;"></td>
					<td style="background-color:#808080;border:1px solid #000000;">{{ 'Partida  ' . $xgen; }}</td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
				</tr>
			@endif

			{{-- Cambio de Centro de Costo --}}
			@if ($xctro != $row->cod_cencosto)
				{{-- Total Partida  --}}
				<tr>
					<td style="border:1px solid #000000;"></td>
					<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ 'Total partida ' . $xgen }}</td>
					<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;"></td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt1, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt2, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt3, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt4, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt5, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt6, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">0,00</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt7, 2, ',', '.') }}</td>
				</tr>

				{{-- Total Centro --}}
				<tr>
					<td style="text-align:center;background-color:#808080;font-weight:bold;border:1px solid #000000;">Total Centro</td>
					<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ $xctro; }}</td>
					<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;"></td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct1, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct2, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct3, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct4, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct5, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct6, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">0,00</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct7, 2, ',', '.') }}</td>
				</tr>

				{{-- Nombre Centro --}}
				<tr>
					<td style="text-align:center;">{{ $row->cod_cencosto }}</td>
					<td style="border:1px solid #000000;">{{ $row->des_centro }}</td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
				</tr>

				{{-- Partida General --}}
				<tr>
					<td style="border:1px solid #000000;"></td>
					<td style="background-color:#808080">{{ 'Partida  ' . $gen; }}</td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
				</tr>

				<tr>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;">{{ $row->cod_partida }}</td>
					<td style="border:1px solid #000000;">{{ $row->des_partida }}</td>
					<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->ley_for, 2, ',', '.') }}</td>
					<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_ley, 2, ',', '.') }}</td>
					<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_pre, 2, ',', '.') }}</td>
					<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_com, 2, ',', '.') }}</td>
					<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_cau, 2, ',', '.') }}</td>
					<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_pag, 2, ',', '.') }}</td>
					<td style="text-align:right;border:1px solid #000000;">0,00</td>
					<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_dis, 2, ',', '.') }}</td>
				</tr>

				@php
					$xctro = $row->cod_cencosto; // Guardar nuevo centro de costo
					$xgen  = $gen;
					$ct1 = 0; $ct2 = 0; $ct3 = 0; $ct4 = 0; $ct5 = 0; $ct6 = 0; $ct7 = 0;
					$pt1 = 0; $pt2 = 0; $pt3 = 0; $pt4 = 0; $pt5 = 0; $pt6 = 0; $pt7 = 0;
				@endphp
			@endif

			{{-- @php
				if ($xgen == '') {
					$xgen = $gen;
				}
			@endphp --}}

			{{-- Cambio de Partida Genérica --}}
			@if ($xgen != $gen)
				<tr>
					<td style="border:1px solid #000000;"></td>
					<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ 'Total partida ' . $xgen }}</td>
					<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;"></td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt1, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt2, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt3, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt4, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt5, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt6, 2, ',', '.') }}</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">0,00</td>
					<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt7, 2, ',', '.') }}</td>
				</tr>

				@php
					$xgen = $gen;
					$pt1 = 0; $pt2 = 0; $pt3 = 0; $pt4 = 0; $pt5 = 0; $pt6 = 0; $pt7 = 0;
				@endphp

				<tr>
					<td style="border:1px solid #000000;"></td>
					<td style="background-color:#808080">{{ 'Partida  ' . $xgen; }}</td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
					<td style="border:1px solid #000000;"></td>
				</tr>
			@endif

			{{-- Fila con data de la partida de la iteración actual --}}
			<tr>
				<td style="border:1px solid #000000;"></td>
				<td style="border:1px solid #000000;">{{ $row->cod_partida }}</td>
				<td style="border:1px solid #000000;">{{ $row->des_partida }}</td>
				<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->ley_for, 2, ',', '.') }}</td>
				<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_ley, 2, ',', '.') }}</td>
				<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_pre, 2, ',', '.') }}</td>
				<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_com, 2, ',', '.') }}</td>
				<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_cau, 2, ',', '.') }}</td>
				<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_pag, 2, ',', '.') }}</td>
				<td style="text-align:right;border:1px solid #000000;">0,00</td>
				<td style="text-align:right;border:1px solid #000000;">{{ Helper::formatNumber($row->mto_dis, 2, ',', '.') }}</td>
			</tr>

			@php
				// Acumulados Generales
				$t1 += $row->ley_for;
				$t2 += $row->mto_ley;
				$t3 += $row->mto_pre;
				$t4 += $row->mto_com;
				$t5 += $row->mto_cau;
				$t6 += $row->mto_pag;
				$t7 += $row->mto_dis;

				// Acumulados Por Centro de Costo
				$ct1 += $row->ley_for;
				$ct2 += $row->mto_ley;
				$ct3 += $row->mto_pre;
				$ct4 += $row->mto_com;
				$ct5 += $row->mto_cau;
				$ct6 += $row->mto_pag;
				$ct7 += $row->mto_dis;

				// Acumulados Por Partida presupuestaria
				$pt1 += $row->ley_for;
				$pt2 += $row->mto_ley;
				$pt3 += $row->mto_pre;
				$pt4 += $row->mto_com;
				$pt5 += $row->mto_cau;
				$pt6 += $row->mto_pag;
				$pt7 += $row->mto_dis;
			@endphp

		@endforeach

		{{-- Total Partida  --}}
		<tr>
			<td style="border:1px solid #000000;"></td>
			<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ 'Total partida ' . $xgen }}</td>
			<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;"></td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt1, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt2, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt3, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt4, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt5, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt6, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">0,00</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($pt7, 2, ',', '.') }}</td>
		</tr>

		{{-- Total Centro --}}
		<tr>
			<td style="text-align:center;background-color:#808080;font-weight:bold;border:1px solid #000000;">Total Centro</td>
			<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ $xctro; }}</td>
			<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;"></td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct1, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct2, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct3, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct4, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct5, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct6, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">0,00</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($ct7, 2, ',', '.') }}</td>
		</tr>

		{{-- Total General --}}
		<tr>
			<td style="text-align:center;background-color:#808080;font-weight:bold;border:1px solid #000000;">Total General</td>
			<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;"></td>
			<td style="background-color:#808080;font-weight:bold;border:1px solid #000000;"></td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($t1, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($t2, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($t3, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($t4, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($t5, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($t6, 2, ',', '.') }}</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">0,00</td>
			<td style="text-align:right;background-color:#808080;font-weight:bold;border:1px solid #000000;">{{ Helper::formatNumber($t7, 2, ',', '.') }}</td>
		</tr>
	</tbody>
</table>