<?php

namespace App\Http\Controllers;

use App\Shipment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PdfReport;
use ExcelReport;
use Illuminate\Support\Carbon;

use App\Mail\ReportMail;


class ReportController extends Controller {
	public function index() {
		$clients = shipment::where('branch_id', Auth::user()->branch_id)->get();
		// $customers = User::where('branch_id', Auth::user()->branch_id)->get();
		$users = User::with('roles')->get();
		$userArr = [];
		foreach ($users as $user) {
			foreach ($user->roles as $role) {
				if ($role->name == 'Customer') {
					$userArr[] = $role->pivot->user_id;		
				}
			}
		}
		$customers = User::whereIn('id', $userArr)->get();
		// return json_decode(json_encode($customers));
		return view('reports.index', compact('customers', 'clients'));
	}

	public function userDateExpo(Request $request) {
		// var_dump($request->all());die;
		// var_dump($request->name);die;
		// var_dump(Shipment::whereBetween('created_at', [$request->start_date, $request->end_date])->where('client_name', $request->name)->get());die;
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		$client_id = $request->name;
		$results = Excel::create('Shipment', function ($excel) use ($date_array, $client_id) {
			// var_dump($date_array); die;
			// var_dump($client_id); die;

			$excel->sheet('Sheetname', function ($sheet) use ($date_array, $client_id) {
				$start_date = $date_array['start_date'];
				$end_date = $date_array['end_date'];
				// var_dump($client_id);die;
				// $shipment = Shipment::whereBetween('created_at', ['2018-01-01', '2018-08-16'])->where('client_name', $client_id)->get();
				// foreach ($date_array as $dates) {
				// 	// var_dump($dates);die;
				// 	$start_date = $dates;
				// 	$end_date = $dates;
				// 	$new_date[] = array(
				// 		$start_date,
				// 		$end_date,
				// 	);
				// }
				// var_dump($shipment);die;
				$sheet->fromModel(Shipment::whereBetween('created_at', $date_array)->where('client_name', '=', $client_id)->get());
			});

		})->download('xls');

		if ($results) {
			echo "success";
		} else {
			echo 'failed';
		}
		// return;
		die;
	}
	public function shipmentExpo(Request $request) {
		// var_dump($request->id); die;
		$results = Excel::create('Shipment', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {
				// $model =
				$sheet->fromModel(Shipment::all());

			});

		})->download('xls');

		if ($results) {
			echo "success";
		} else {
			echo 'failed';
		}
		// return;
		die;
	}
	public function userExpo() {
		$results = Excel::create('Users', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(User::all());

			});

		})->export('xls');

		if ($results) {
			echo "success";
		} else {
			echo 'Failed';
		}
		// return;
	}
	public function deriverdExpo() {
		$results = Excel::create('Users', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(Shipment::where('status', 'Derivered')->get());

			});

		})->export('xls');

		if ($results) {
			echo "success";
		} else {
			echo 'Failed';
		}
	}
	public function customersExpo() {
		$results = Excel::create('Users', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(User::where('type', 'customer')->get());

			});

		})->export('xls');

		if ($results) {
			echo "success";
		} else {
			echo 'Failed';
		}
	}
	public function dispatchedExpo() {
		$results = Excel::create('Users', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(Shipment::where('status', 'Dispatched')->get());

			});

		})->export('xls');

		if ($results) {
			echo "success";
		} else {
			echo 'Failed';
		}

	}
	public function agentsExpo() {

	}
	public function cancledExpo() {

	}
	public function pendingExpo() {
		$results = Excel::create('Users', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(Shipment::where('status', 'pending')->get());

			});

		})->export('xls');

		if ($results) {
			echo "success";
		} else {
			echo 'Failed';
		}
	}
	public function bookingExpo() {

	}
	public function approvedExpo() {
		$results = Excel::create('Approved Shipments', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(Shipment::where('status', 'approved')->get());

			});

		})->export('xls');

		/*if ($results) {
				echo "success";
			} else {
				echo 'Failed';
		*/
	}

	// PdfReport Aliases

public function displayReport(Request $request) {
	$all_shipment = Shipment::setEagerLoads([])->get();
	$today = Carbon::now();
	 $user = User::find(1);
	// foreach ($all_shipment as $shipment) {
	// 	$derivery_date = new Carbon($shipment->derivery_date);
	// 	$date1 = Carbon::today();
	// 	$date2 = new Carbon('tomorrow');
	// 	$date2->diffInDays($date1);
	// 	$shipment = Shipment::whereBetween('created_at', [$date1, $date2])->setEagerLoads([])->get();
	// }



	
	$fromDate = Carbon::today();
	$next_month = $today->addMonth();

	$toDate = '2018-08-17';
	$sortBy = 'id';

	// Report title
	$title = 'Registered User Report';

	// For displaying filters description on header
	$meta = [
		'Report From' => $fromDate . ' To ' . $toDate,
		'Sort By' => $sortBy
	];
	

	$users = User::with('roles')->get();
	$userArr = [];
	foreach ($users as $user) {
		foreach ($user->roles as $role) {
			if ($role->name == 'Customer') {
				$userArr[] = $role->pivot->user_id;		
			}
		}
	}
	$customers = User::whereIn('id', $userArr)->get();
	$cust_emails = $customers->map(function ($customer) {
		return $customer->only('email', 'name');
	});

	foreach ($cust_emails as $mails) {
	$email = $mails['email'];
		
	// Do some querying..
	$queryBuilder = Shipment::whereBetween('created_at', ['2018-08-01' ,$next_month])
						->where('client_name', $mails['name'])
						->orderBy($sortBy);
	$columns = [
		'airway bill no',
		'sender name',
		'sender email',
		'sender city',
		'sender phone',
		'client name',
		'client email',
		'client city',
		'client phone',
		'amount ordered',
		'derivery date',
	];
	
	$pdf = PdfReport::of($title, $meta, $queryBuilder, $columns)
					->editColumn('created at', [
						'displayAs' => function($result) {
							return $result->created_at->format('d M Y');
						}
					])
					->setCss([
						'.head-content' => 'border-width: 1px',
					 ])
					->limit(10)
					->stream(); // or download('f
					return $pdf;

	}	
}
}
