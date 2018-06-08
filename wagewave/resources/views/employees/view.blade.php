@extends('layouts.layout')

@section('breadcrumb')
    <nav>
        <div class="nav-wrapper indigo darken-2">
            <a id="rootCrumb" class="breadcrumb" href="{{ url('/home') }}">Index</a>
            <a class="breadcrumb" href="{{ url('/employees') }}">Employees</a>
            <a class="breadcrumb" href={{ url("/employee/$employee->id") }}>{{ $employee->id }}</a>
        </div>
    </nav>
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col s12 m6 l4">
				<div class="card">
					<div class="card-image">
						<img src={{ asset("$employee->image") }} class="responsive-img">
						<span class="card-title">{{ $employee->id }}</span>
						<button id="editBtn" class="btn-floating halfway-fab waves-effect waves-light red darken-4"><i class="material-icons">edit</i></button>
					</div>
					<div id="currentInfo" class="card-content">
						<h5>{{ $employee->name }}</h5>
						<p><strong>Contact no.:</strong> {{ $employee->mobile }}</p>
						<hr>
						<p><strong>Address:</strong> {{ $employee->address }}</p>
						<hr>
						<p><strong>Birth date:</strong> {{ $employee->birth_date }}</p>
						<hr>
						<p><strong>TIN:</strong> {{ $employee->TIN }}</p>
						<hr>
						<p><strong>SSS:</strong> {{ $employee->SSS }}</p>
						<hr>
						<p><strong>Pag-ibig MID:</strong> {{ $employee->Pagibig }}</p>
					</div>
					<div id="editInfo" class="card-content" hidden>
						<form id="editForm" action={{ url("/employee/$employee->id/update") }} method="POST">
							{{ csrf_field() }}
							<div class="row">
								<div class="input-field col s12">
									<input type="text" id="name" name="name" value="{{ $employee->name }}" class="validate">
									<label class="active" for="name">Name:</label>
								</div>
								<div class="input-field col s12">
									<input type="tel" id="mobile" name="mobile" value="{{ $employee->mobile }}" class="validate">
									<label class="active" for="mobile">Contact no.:</label>
								</div>
								<div class="input-field col s12">
									<input type="text" id="address" name="address" value="{{ $employee->address }}" class="validate">
									<label class="active" for="address">Address:</label>
								</div>
								<div class="input-field col s12">
									<input type="date" id="birthDate" name="birth_date" value="{{ $employee->birth_date }}" class="validate" min="2000-06-12" max="1958-06-12">
									<label class="active" for="birthDate">Birth Date:</label>
								</div>
								<div class="input-field col s12">
									<input type="text" id="TIN" name="TIN" value="{{ $employee->TIN }}" class="validate" oninput="formatTIN()" pattern="[0-9-]{12,}" maxlength="15">
									<label class="active" for="TIN">TIN:</label>
								</div>
								<div class="input-field col s12">
									<input type="text" id="SSS" name="SSS" value="{{ $employee->SSS }}" class="validate" oninput="formatSSS()" pattern="[0-9-]{10,}" maxlength="12">
									<label class="active" for="SSS">SSS:</label>
								</div>
								<div class="input-field col s12">
									<input type="text" id="Pagibig" name="Pagibig" value="{{ $employee->Pagibig }}" class="validate" oninput="formatPagibig()" pattern="[0-9-]{12,}" maxlength="14">
									<label class="active" for="Pagibig">Pag-ibig MID:</label>
								</div>
								<button type="submit" class="waves-effect btn green accent-5 right">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col s12 m6 l4">
				<div class="card">
					<div class="card-content">
						<h5>{{ $today }}</h5>
					</div>
					<div class="card-action">
						@if (!$logs->isEmpty())
							@foreach($logs as $timesheet)
								@if($timesheet->clock_out == null)
								<div class="clock-out">
									<button class="btn teal" id="clockOut" value="{{ $employee->id }}">Clock Out</button>
								</div>
								@endif
							<div class="todays-log">
								<h6>{{ $timesheet->job->job }}</h6>
								<p><strong>Time In: </strong>{{ $timesheet->clock_in }}</p>
								<p><strong>Time Out: </strong>{{ $timesheet->clock_out }}</p>
							</div>
							@endforeach
						@else
						<div class="clock-in">
							<div class="row">
							<div class="input-field col s12">
								<select id="todaysJob" name="job_id">
									<option value="" disabled selected>Select the job</option>
									@foreach($jobs as $job)
									<option value="{{ $job->id }}">{{ $job->job }}</option>
									@endforeach
								</select>
								<label>Today's Job</label>
							</div>
							<button class="btn teal" id="clockIn" value="{{ $employee->id }}">Clock In</button>
							</div>
						</div>
						<div class="clock-out" hidden>
									<button class="btn teal" id="clockOut" value="{{ $employee->id }}">Clock Out</button>
								</div>
						<div class="todays-log" hidden>
							
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection

@section('indiv_js')
	<script type="text/javascript">
		
		$(document).ready(function() {
			$('select').formSelect();

			$('#editBtn').click(function() {
				$('#currentInfo').toggle();
				$('#editInfo').toggle();
			});
		});

		$('#clockIn').on('click', function() {
			var empID = $(this).val();
			var jobID = $('#todaysJob').val();
			var csrf = $('[name="csrf-token"]').attr('content');
			// alert(empID);
			// alert(csrf);
			// alert(jobID);

			$.post('/timesheet/clock-in/' + empID,
				{
					employee_id: empID,
					job_id: jobID,
					_token: csrf
				},
				function(data, status) {
					$('.clock-in').toggle();
					$('.clock-out').toggle();
					$('.todays-log').html(data);
					$('.todays-log').toggle();
				});
		});

		$('#clockOut').on('click', function() {
			var empID = $(this).val();
			var csrf = $('[name="csrf-token"]').attr('content');
			// alert(empID);
			// alert(csrf);

			$.post('/timesheet/clock-out/' + empID,
				{
					employee_id: empID,
					_token: csrf
				},
				function(data, status) {
					$('.clock-out').toggle();
					$('.todays-log').html(data);
				});
		});

		function formatTIN() {
			var inputTIN = document.getElementById("TIN");
			var newTIN = document.getElementById("TIN").value;
			// console.log(newTIN);
			newTIN = newTIN.replace(/[\W\D\s\._\-]+/g, '');

			var split = 3;
			var chunk = [];

			for (var i = 0, len = newTIN.length; i < len; i += split) {
				chunk.push(newTIN.substr(i, split));
			}

			var formattedTIN = chunk.join("-");
			// console.log(formattedTIN);
			inputTIN.value = formattedTIN;
		}

		function formatSSS() {
			var inputSSS = document.getElementById("SSS");
			var newSSS = document.getElementById("SSS").value;
			// console.log(newSSS);
			newSSS = newSSS.replace(/[\W\D\s\._\-]+/g, '');

			var split;
			var chunk = [];

			for (var i = 0, len = newSSS.length; i < len; i++) {
				switch (i) {
					case 0:
						split = 2;
						break;
					case 2:
						split = 7;
						break;
					case 9:
						split = 1;
						break;
					default:
						continue;
				}
				chunk.push(newSSS.substr(i, split));
			}

			var formattedSSS = chunk.join("-");
			// console.log(formattedSSS);
			inputSSS.value = formattedSSS;
		}

		function formatPagibig() {
			var inputPagibig = document.getElementById("Pagibig");
			var newPagibig = document.getElementById("Pagibig").value;
			// console.log(newPagibig);
			newPagibig = newPagibig.replace(/[\W\D\s\._\-]+/g, '');

			var split = 4;
			var chunk = [];

			for (var i = 0, len = newPagibig.length; i < len; i += split) {
				chunk.push(newPagibig.substr(i, split));
			}

			var formattedPagibig = chunk.join("-");
			// console.log(formattedPagibig);
			inputPagibig.value = formattedPagibig;
		}

	</script>
@endsection