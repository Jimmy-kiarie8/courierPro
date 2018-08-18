<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipmentRequest;
use App\Shipment;
use Illuminate\Support\Carbon;
use App\ShipmentStatus;
use App\Product;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ShipmentController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getShipments() {
		$roles = Auth::user()->roles;
		foreach ($roles as $role) {
			$role_name = $role->name;
		}
		// return $role_name;
		if ($role_name == 'Admin') {
			return Shipment::all();
		}
		$results = Shipment::where('branch_id', Auth::user()->branch_id)->get();
		return json_decode(json_encode($results), true);
	}

	public function csv() {
		return view('csv.csv');
	}

	public function barcodeUpdate(Request $request, Shipment $shipment, $bar_code = null) {
		// return $request->all();
		$barcode = Shipment::where('bar_code', $request->bar_code)->first();
		$barcode->derivery_status = 'dispatched';
		$barcode->payment = 'yes';
		$barcode->save();
		return $barcode;
	}

	/**
	 * Search the products table.
	 *
	 * @param  Request $request
	 * @return mixed
	 */
	public function search(Request $request) {
		// First we define the error message we are going to show if no keywords
		// existed or if no results found.
		$error = ['error' => 'No results found, please try with different keywords.'];

		// Making sure the user entered a keyword.
		if ($request->has('q')) {

			// Using the Laravel Scout syntax to search the products table.
			$posts = Shipment::search($request->get('q'))->get();

			// If there are results return them, if none, return the error message.
			return $posts->count() ? $posts : $error;

		}

		// Return the error message if no keywords existed
		return $error;
	}

	public function barcodeIn(Request $request, Shipment $shipment, $bar_code_in = null) {
		$results = Shipment::select('derivery_status')->whereNull('derivery_status')
			->where('derivery_status', '!=', 'derivered')
			->where('bar_code', $request->bar_code)->get();
		$results2 = Shipment::select('derivery_status')->where('derivery_status', 'store')
			->where('derivery_status', '!=', 'derivered')
			->where('bar_code', $request->bar_code)->get();
		$derivery_status = Shipment::select('derivery_status')->where('derivery_status', 'dispatched')
			->where('bar_code', $request->bar_code)->get();

		$barcode = Shipment::where('bar_code', $request->bar_code)->first();
		// $barcode = Shipment::find(1);
		// return count(Shipment::all());
		if (count($results) > 0) {
			$barcode->derivery_status = 'Stored';
		} elseif (count($results2) > 0) {
			$barcode->derivery_status = 'Return 1';
		} elseif (count($derivery_status) > 0) {
			foreach ($derivery_status as $status) {
				$derivery_statuses = $status->derivery_status;
			}
			// return $derivery_statuses;
			$derivery_arr = explode(' ', $derivery_status);
			$ret = $derivery_arr[0];
			return $num = $derivery_arr[1];
			$new_num = $num + 1;
			$barcode->derivery_status = 'Return' . ' ' . $new_num;
		}
		// return $request->all();
		$barcode->save();
		return $barcode;
	}

	/**
	 * import a file in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function import(Request $request) {
		// return $request->all();
		if ($request->file('shipment')) {
			// var_dump('woooooooo');
			$path = $request->file('shipment')->getRealPath();
			$data = Excel::load($path, function ($reader) {

			})->get();

			if (!empty($data) && $data->count()) {
				foreach ($data->toArray() as $row) {
					if (!empty($row)) {
						$dataArray[] =
							[
							'client_name' => $row['name_of_the_client'],
							'order_id' => $row['order_id'],
							'client_email' => $row['sender_mail'],
							'client_phone' => $row['phone'],
							'client_address' => $row['address'],
							'client_city' => $row['city'],
							'amount_ordered' => $row['quantity'],
							// 'client_postal_code' => $row['postal_code'],
							'client_region' => $row['region'],
							// 'booking_date' => $row['order_date']->formatDates(true),
							// 'product_no' => $row['How_many_pieces_of_the_product_are_contained_in_the_order'],
							'airway_bill_no' => $row['order_id'],
							'bar_code' => $row['order_id'],
							// 'derivery_date' => $row['derivery_date'],
							// 'order_date' => $row['order_date'],
							'user_id' => Auth::id(),
							'status' => 'Stored',
							'created_at' => new DateTime(),
							'booking_date' => new DateTime(),
							'updated_at' => new DateTime(),
							'shipment_id' => random_int(1000000, 9999999),
							'sender_name' => Auth::user()->name,
							'sender_email' => Auth::user()->email,
							'sender_phone' => Auth::user()->phone,
							'sender_address' => Auth::user()->address,
							'sender_city' => Auth::user()->city,
							'user_id' => Auth::id(),
						];
					}
				}
				if (!empty($dataArray)) {
					Shipment::insert($dataArray);
				}
				return redirect('courier#/Shipments');
				
			}
		}
	}

	public function export() {
		$model = Shipment::where('branch_id', Auth::user()->branch_id)->get();
		$results = Excel::create('Shipment', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(Shipment::all());

			});

		})->export('csv');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		// return $request->products;
		$this->
		Validate($request, [
			'client_name' =>'required',
			'client_phone' =>'required|numeric',
			'client_email' =>'required|email',
			'client_address' =>'required',
			'client_city' =>'required',
			'assign_staff' =>'required',
			'airway_bill_no' =>'required',
			'shipment_type' =>'required',
			'payment' =>'required',
			'insuarance_status' =>'required',
			'booking_date' =>'required|date',
			'derivery_date' =>'required|date',
			'bar_code' =>'required',
			'derivery_time' =>'required',
			// 'sender_name' =>'required',
			// 'sender_phone' =>'required',
			// 'sender_address' =>'required',
			// 'sender_city' =>'required',
			// 'total_freight' =>'required|numeric',
		]);
		

		$products = collect($request->products)->transform(function ($product) {
			$product['total'] = $product['quantity'] * $product['price'];
			$product['user_id'] = Auth::id();
			return new Product($product);
		});

		// return $products;

		if ($products->isEmpty()) {
			return response()->json([
				'product_empty' => ['One or more products is required'],
			], 422);
		}
		$shipment = new Shipment;
		$shipment->sub_total = $products->sum('total');
		$shipment->client_name = $request->client_name;
		$shipment->client_phone = $request->client_phone;
		$shipment->client_email = $request->client_email;
		$shipment->client_address = $request->client_address;
		$shipment->client_city = $request->client_city;
		$shipment->assign_staff = $request->assign_staff;
		$shipment->airway_bill_no = $request->airway_bill_no;
		$shipment->shipment_type = $request->shipment_type;
		$shipment->payment = $request->payment;
		$shipment->total_freight = $request->total_freight;
		// $shipment->total = $request->total;
		$shipment->insuarance_status = $request->insuarance_status;
		$shipment->booking_date = $request->booking_date;
		$shipment->derivery_date = $request->derivery_date;
		$shipment->derivery_time = $request->derivery_time;
		$shipment->bar_code = $request->bar_code;
		$shipment->branch_id = $request->branch_id;
		// return $request->customer_id;
		$shipment->sender_name = Auth::user()->name;
		$shipment->sender_email = Auth::user()->email;
		$shipment->sender_phone = Auth::user()->phone;
		$shipment->sender_address = Auth::user()->address;
		$shipment->sender_city = Auth::user()->city;
		$shipment->user_id = Auth::id();
		$shipment->shipment_id = random_int(1000000, 9999999);
		// $shipment->branch_id = Auth::user()->branch_id;
		
		if ($shipment->save()) {
			$shipment->products()->saveMany($products);
		}
		return $shipment;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Shipment  $shipment
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Shipment $shipment) {
		// return $request->all();
		$shipment = Shipment::find($request->id);
		$shipment->client_name = $request->client_name;
		$shipment->client_phone = $request->client_phone;
		$shipment->client_email = $request->client_email;
		$shipment->client_address = $request->client_address;
		$shipment->client_city = $request->client_city;
		$shipment->assign_staff = $request->assign_staff;
		$shipment->airway_bill_no = $request->airway_bill_no;
		$shipment->shipment_type = $request->shipment_type;
		// $shipment->customer_id = $request->customer_id;
		$shipment->payment = $request->payment;
		
		$shipment->total_freight = $request->total_freight;
		$shipment->insuarance_status = $request->insuarance_status;
		$shipment->booking_date = $request->booking_date;
		$shipment->derivery_date = $request->derivery_date;
		$shipment->derivery_time = $request->derivery_time;
		$shipment->save();
		return $shipment;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Shipment  $shipment
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Shipment $shipment) {
		Shipment::find($shipment->id)->delete();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Shipment  $shipment
	 * @return \Illuminate\Http\Response
	 */
	public function updateStatus(Request $request, Shipment $shipment, $id) {
		// return $request->all();
		$shipment = Shipment::find($request->id);
		if ($request->address) {
			$coordinates = serialize($request->address);
			$latitude = $request->address['latitude'];
			$longitude = $request->address['longitude'];
			$coords = array('lat' => $latitude, 'lng' => $longitude);
			$shipment->coordinates = $coordinates;
			$shipment->longitude = $longitude;
			$shipment->latitude = $latitude;
		}
		$shipment->status = $request->formobg['status'];
		// var_dump($request->formobg['status']); die;
		$shipment->remark = $request->formobg['remark'];
		$shipment->save();
		return $shipment;
	}

	public function UpdateShipment(Request $request, Shipment $shipment)
	{
		// return $request->selected;
		$id = [];
		foreach ($request->selected as $selectedItems ) {
			$id[] = $selectedItems['id'];
		}
		$status = $request->form['status'];
		$remark = $request->form['remark'];
		$derivery_date = $request->form['scheduled_date'];
		$shipment = Shipment::whereIn('id', $id)->update(['status' => $status, 'remark' => $remark]);
		$shipStatus = Shipment::whereIn('id', $id)->get();
		foreach ($shipStatus as $statuses) {
			$statusUpdate = new ShipmentStatus;
			$statusUpdate->remark = $request->form['remark'];
			$statusUpdate->status = $request->form['status'];
			$statusUpdate->location = $request->form['location'];
			$statusUpdate->user_id = Auth::id();
			$statusUpdate->branch_id = Auth::user()->branch_id;
			$statusUpdate->shipment_id = $statuses->id;
			// return $statusUpdate;
			$statusUpdate->save();
			// return $statusUpdate;
		}
		// $shipStatus->statuses()->saveMany($shipStatus);

		return $shipment;
	}

	public function assignDriver(Request $request, Shipment $shipment)
	{
		$id = [];
		foreach ($request->selected as $selectedItems ) {
			$id[] = $selectedItems['id'];
		}
		$driver = $request->form['driver'];
		$remark = $request->form['remark'];
		$shipment = Shipment::whereIn('id', $id)->update(['driver' => $driver, 'remark' => $remark]);
		return $shipment;
	}

	public function assignBranch(Request $request, Shipment $shipment)
	{
		// return $request->all();
		$id = [];
		foreach ($request->selected as $selectedItems ) {
			$id[] = $selectedItems['id'];
		}
		// $status = $request->form['status'];
		// $remark = $request->form['remark'];
		// $shipment = Shipment::whereIn('id', $id)->update(['branch_id' => $branch, 'remark' => $remark]);
		// return $shipment;
		$branch = $request->form['branch_id'];
		$remark = $request->form['remark'];
		$shipment = Shipment::whereIn('id', $id)->update(['branch_id' => $branch, 'remark' => $remark]);
		// return $shipStatus = Shipment::whereIn('id', $id)->get();
		
	}

	public function getcoordinatesArray($id) {
		$record = Shipment::find($id);
		if ($record) {
			// var_dump('pass');
			$coordinates = unserialize($record->coordinates);
			$arraySt = json_decode(json_encode($coordinates), true);
			$latitude = $arraySt['latitude'];
			$longitude = $arraySt['longitude'];
			return array('lat' => $latitude, 'lng' => $longitude);
		} else {
			// var_dump('fail');
			// $latitude = '-1.2808685';
			// $longitude = '36.73657560000004';
			return array('lat' => '-1.2808685', 'lng' => '36.73657560000004');
		}

	}

	// Dashboard
	public function delayedShipment() {
		return Shipment::where('status', 'delayed')->where('branch_id', Auth::user()->branch_id)->get();
	}

	public function approvedShipment() {
		return Shipment::where('status', 'approved')->where('branch_id', Auth::user()->branch_id)->get();
	}

	public function waitingShipment() {
		return Shipment::where('status', 'waiting approval')->where('branch_id', Auth::user()->branch_id)->get();
	}

	public function deriveredShipment() {
		return Shipment::where('status', 'derivered')->where('branch_id', Auth::user()->branch_id)->get();
	}

	public function scheduled() {
		// return Shipment::where('status', 'Scheduled')->get();
		$all_shipment = Shipment::get();
		foreach ($all_shipment as $shipment) {
			$derivery_date = new Carbon($shipment->derivery_date);
			$date1 = Carbon::today();
			$date2 = new Carbon('tomorrow');
        	$date2->diffInDays($date1);
        	$shipment = Shipment::whereBetween('created_at', [$date1, $date2])->where('status', 'Scheduled')->get();
		}
		return $shipment;
	}

	// Chart
	public function getChartData() {
		$shipments = DB::table('products')
			->select(DB::raw('count(id) as count, date_format(created_at, "%M %d") as date'))
			->orderBy('created_at', 'desc')
			->groupBy('date')
			->where('branch_id', Auth::user()->branch_id)
			->get();

		$lables = [];
		$rows = [];
		foreach ($shipments as $shipment) {
			$lables[] = $shipment->date;
			$rows[] = $shipment->count;
		}
		$data = [
			'lables' => $lables,
			'rows' => $rows,
		];
		return $data;
	}

	public function filterShipment(Request $request)
	{
		// return $request->all();
		if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['state'] == 'all') {
					return Shipment::all();	
				}else{
					return Shipment::where('status', $request->selectStatus['state'])->get();
				}
				
			}else{
				return Shipment::where('branch_id', $request->select['id'])->get();
			}
		}else{
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['state'] == 'all') {
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->get();
				}else{
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->get();
				}
			}else{
				return Shipment::where('branch_id', $request->select['id'])
								->where('status', $request->selectStatus['state'])
								->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])
								->get();
			}
		}
	}

}
