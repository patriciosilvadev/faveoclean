<?php namespace App\Http\Controllers\Admin\helpdesk;
// controller
use App\Http\Controllers\Controller;
// request
use App\Http\Requests\helpdesk\BanlistRequest;
use App\Http\Requests\helpdesk\BanRequest;
// model
use App\User;

/**
 * BanlistController
 *
 * @package     Controllers
 * @subpackage  Controller
 * @author      Ladybird <info@ladybirdweb.com>
 */
class BanlistController extends Controller {

	/**
	 * Create a new controller instance.
	 * @return type void
	 */
	public function __construct() {
		$this->middleware('auth');
		$this->middleware('roles');
	}

	/**
	 * Display a listing of the resource.
	 * @param type Banlist $ban
	 * @return type Response
	 */
	public function index() {
		try {
			$bans = User::where('ban','=',1)->get();
			return view('themes.default1.admin.helpdesk.emails.banlist.index', compact('bans'));
		} catch (Exception $e) {
			return view('404');
		}
	}

	/**
	 * Show the form for creating a new resource.
	 * @return type Response
	 */
	public function create() {
		try {
			return view('themes.default1.admin.helpdesk.emails.banlist.create');
		} catch (Exception $e) {
			return view('404');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * @param type banlist $ban
	 * @param type BanRequest $request
	 * @param type User $user
	 * @return type Response
	 */
	public function store(BanRequest $request, User $user) {
		// try {
			//adding field to user whether it is banned or not
			$adban = $request->input('email');
			$use = $user->where('email', $adban)->first();
			if ($use != null) {
				$use->ban = $request->input('ban_status');
				$use->internal_note = $request->input('internal_note');
				$use->save();
				// $user->create($request->input())->save();
				return redirect()->back()->with('success', 'Email Banned sucessfully');
			} else {
				$user = new User;
				$user->email = $adban;
				$user->ban = $request->input('ban_status');
				$user->internal_note = $request->input('internal_note');
				$user->save();
				return redirect()->back()->with('success', 'Email Banned sucessfully');
			}
		// } catch (Exception $e) {
			// return redirect('banlist')->with('fails', 'Email can not Ban');
		// }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param type int $id
	 * @param type Banlist $ban
	 * @return type Response
	 */
	public function edit($id, User $ban) {
		try {
			$bans = $ban->whereId($id)->first();
			return view('themes.default1.admin.helpdesk.emails.banlist.edit', compact('bans'));
		} catch (Exception $e) {
			return view('404');
		}
	}

	/**
	 * Update the specified resource in storage.
	 * @param type int $id
	 * @param type Banlist $ban
	 * @param type BanlistRequest $request
	 * @return type Response
	 */
	public function update($id, User $ban, BanlistRequest $request) {
		try {
			$bans = $ban->whereId($id)->first();
			$bans->internal_note = $request->input('internal_note');
			$bans->ban = $request->input('ban');
			// dd($request->input('ban'));
			if ($bans->save()) {
				return redirect('banlist')->with('success', 'Banned Email Updated sucessfully');
			} else {
				return redirect('banlist')->with('fails', 'Banned Email not Updated');
			}
		} catch (Exception $e) {
			return redirect('banlist')->with('fails', 'Banned Email not Updated');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 * @param type int $id
	 * @param type Banlist $ban
	 * @return type Response
	 */
	public function destroy($id, Banlist $ban) {
		try {
			$bans = $ban->whereId($id)->first();
			/* Success and Falure condition */
			if ($bans->delete() == true) {
				return redirect('banlist')->with('success', 'Banned Email Deleted sucessfully');
			} else {
				return redirect('banlist')->with('fails', 'Banned Email can not Delete');
			}
		} catch (Exception $e) {
			return redirect('banlist')->with('fails', 'Banned Email can not Delete');
		}
	}
}