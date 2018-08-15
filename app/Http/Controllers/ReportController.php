<?php

namespace App\Http\Controllers;

use App\Shipment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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

				$sheet->fromModel(Shipment::where('status', 'derivered')->get());

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
	public function branchesExpo() {

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
}
