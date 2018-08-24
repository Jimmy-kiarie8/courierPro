<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;
use App\Notifications\scheduleNotification;

use App\Role_user;
use App\Role;
use App\User;
use App\Shipment;
use Illuminate\Http\Request;
use App\Mail\scheduleMail;
use Illuminate\Support\Facades\Mail;
use PdfReport;
use ExcelReport;
use App\Mail\ReportMail;

class RoleController extends Controller {
	
	public function getUsersRole() {
		$user_arr = json_decode(json_encode(User::where('branch_id', Auth::user()->branch_id)->get()), true);
		return $user_arr;

	}

	public function store(Request $request)
	{
		$role = new Role;
		$role->name = $request->name;
		$role->description = $request->description;
		$role->save();
		return $role;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Role  $role
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Role_user $role_user, $id) {
		$role = Role::find($id);
		$role->name = $request->name;
		$role->description = $request->description;
		$role->save();
		return $role;
	}

	public function destroy(Role $role) {
		// return $role->id;
		Role::find($role->id)->delete();
	}
	
	public function getRoles()
	{
		return	Role::all();
	}

	public function carbon(Request $request)
	{

		$all_shipment = Shipment::setEagerLoads([])->get();
		$user = User::find(1);
		// $user->notify(new ShipmentNoty($all_shipment));
		// return;
		$today = Carbon::now();
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
							->orderBy($sortBy)->get();
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
		
		$pdf = ExcelReport::of($title, $meta, $queryBuilder, $columns)
						->editColumn('created at', [
							'displayAs' => function($result) {
								return $result->created_at->format('d M Y');
							}
						])
						->setCss([
							'.head-content' => 'border-width: 1px',
						 ])
						->make('csv'); // or download('f
						// $pdf = json_decode(json_encode($pdf_new), true);
						// var_dump($pdf);die;
			Mail::send(new ReportMail($user, $email));


		}	
	}

}
