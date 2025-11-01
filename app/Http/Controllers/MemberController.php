<?php
namespace App\Http\Controllers;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MemberController extends Controller
{

    public function getIntroducer($id)
    {
        // 1️⃣ Prevent user from entering their own phone or ID
        $currentUserPhone = Session::get('app_user_phone');

        if ($id == $currentUserPhone) {
            return response()->json(['error' => 'You cannot use your own number as introducer']);
        }

        $introducer = DB::table('app_users')
            ->where('id', $id)
            ->orWhere('phone_number', $id)
            ->first();

        if (! $introducer) {
            return response()->json(['error' => 'Introducer not found']);
        }

        return response()->json([
            // 'introducer_id_hidden' => $introducer->id,
            'name'       => $introducer->app_u_name,
            'phone'      => $introducer->phone_number,
            // 'select_plan_name'  => $introducer->select_plan_name,
            // 'select_plan_id'  => $introducer->select_plan_id,
            'address'    => $introducer->app_u_address,
            'wallet_bal' => $introducer->user_wallet,
            // 'position' => null // no position field exists in this table
        ]);
    }

    // Show Add Form
    public function adminCreate()
    {
        $last   = Member::orderBy('id', 'desc')->first();
        $nextId = str_pad(($last ? $last->id + 1 : 1), 7, '0', STR_PAD_LEFT); // e.g. 0000007

        if (request()->routeIs('addAdmin.adminCreate')) {
            return view('admin.logicApp.addAdmin', [
                'nextId'  => $nextId,
                'company' => null,
                'isEdit'  => false,
            ]);
        }
        // return view('admin.member_join', compact('nextId', 'memberJoinDropDpwn'));
    }

    // Store New Company
    public function adminStore(Request $request)
    {
        $request->validate([
            'member_id'    => 'required|unique:members,member_id',
            'CompanyName'  => 'required',
            'phone'        => 'required|unique:members,phone',
            'qrCodeUpload' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('qrCodeUpload')) {
            $file     = $request->file('qrCodeUpload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/qr_company'), $filename);
            $filePath = 'uploads/qr_company/' . $filename;
        }

        $joinDate = Carbon::now();

        Member::create([
            'member_id'    => $request->member_id,
            'name'         => $request->CompanyName,
            'phone'        => $request->phone,
            'password'     => Hash::make('abc11'),
            'email'        => $request->email,
            'address'      => $request->address,
            'pincode'      => $request->pincode,
            'state'        => $request->state,
            'cin_no'       => $request->cin_no,
            'BankName'     => $request->BankName,
            'BankACNo'     => $request->BankACNo,
            'BankIFSC'     => $request->BankIFSC,
            'upiId'        => $request->upiId,
            'qrCodeUpload' => $filePath,
            'join_date'    => $joinDate,
            'expiry_date'  => '2025-07-18',
            // 'status' => Active= 1, Deactive =2, Pending = 3	,
            'status'       => 2,
        ]);

        if (request()->routeIs('addAdmin.adminStore')) {
            return back()->with('success', 'Registration successful!');
        }

        return back()->with('success', 'Company added Successful.');
    }

    // Show Edit Form
    public function adminEdit($id)
    {
        $company = Member::findOrFail($id);
        return view('admin.logicApp.addAdmin', [
            'company' => $company,
            'nextId'  => $company->member_id,
            'isEdit'  => true,
        ]);
    }

    // Update Existing Company

    public function adminUpdate(Request $request, $id)
    {
        $company = Member::findOrFail($id);

        $request->validate([
            'CompanyName'    => 'required',
            'phone'          => 'required|unique:members,phone,' . $id,
            'qrCodeUpload'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'password'       => 'nullable|string|min:4', // Optional password update
            'company_status' => 'required|in:1,2,3',
        ]);

        // Handle QR file
        $filePath = $company->qrCodeUpload;
        if ($request->hasFile('qrCodeUpload')) {
            $file     = $request->file('qrCodeUpload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/qr_company'), $filename);
            $filePath = 'uploads/qr_company/' . $filename;
        }

        // Prepare update data
        $updateData = [
            'name'         => $request->CompanyName,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'pincode'      => $request->pincode,
            'state'        => $request->state,
            'cin_no'       => $request->cin_no,
            'BankName'     => $request->BankName,
            'BankACNo'     => $request->BankACNo,
            'BankIFSC'     => $request->BankIFSC,
            'upiId'        => $request->upiId,
            'qrCodeUpload' => $filePath,
            'status'       => $request->company_status,
        ];

        // If password is filled, update it
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $company->update($updateData);

        return back()->with('success', 'Company updated successfully!');
    }

    // ******************************************

    public function viewAdminsList()
    {
        // $getCompany  = DB::table('members')->get();

        $getCompany = Member::where('member_id', '!=', '0000001')->get();

        // $compantList= DB::table('plan_name_master')->get();

        // return view('admin.plan_master', compact('plans', 'planNames'));

        return view('admin.logicApp.viewAdminsList', compact('getCompany'));

    }

// ******************************** APP Banner

    public function appBannerView()
    {
        // Fetch all packages from the database
        $packages = \DB::table('app_banners')->get();
        // Pass the packages data to the view

        return view('admin.logicApp.appBannerMaster', compact('packages'));
    }

    public function appBannerPost(Request $request)
    {
        // File upload helper public\userApp\assets\pg-banner
        $uploadFile = function ($request, $inputName, $folder, $prefix = '') {
            if ($request->hasFile($inputName)) {
                $file     = $request->file($inputName);
                $filename = 'bg' . $prefix . '_' . date('Ymd_His') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($folder), $filename);
                return "$folder/$filename";
            }
            return null;
        };

        $packagePhoto = $uploadFile($request, 'packagePhoto', 'userApp/assets/pg-banner', 'pk_img');

        // Insert data into package_master table
        DB::table('app_banners')->insert([
            'banner_url' => $packagePhoto,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Package created successfully!');
    }

// ********************************

    public function packageMasterGet()
    {
        // Fetch all packages from the database
        $packages = \DB::table('package_master')->get();
        // Pass the packages data to the view
        return view('admin.logicApp.packageMaster', compact('packages'));
    }

    public function packageMasterStore(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'package_name'          => 'required|string|max:255',
            'package_amount'        => 'required|numeric',
            'package_payout_per'    => 'required|numeric',
            'package_total_amount'  => 'required|numeric',
            'package_time_duration' => 'required|numeric',
        ]);

        // File upload helper public\userApp\assets\pg-banner
        $uploadFile = function ($request, $inputName, $folder, $prefix = '') {
            if ($request->hasFile($inputName)) {
                $file     = $request->file($inputName);
                $filename = 'bg' . $prefix . '_' . date('Ymd_His') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($folder), $filename);
                return "$folder/$filename";
            }
            return null;
        };

        $packagePhoto = $uploadFile($request, 'packagePhoto', 'userApp/assets/pg-banner', 'pk_img');

        // Insert data into package_master table
        DB::table('package_master')->insert([
            'package_name'          => $request->package_name,
            'package_amount'        => $request->package_amount,
            'package_payout_per'    => $request->package_payout_per,
            'package_total_amount'  => $request->package_total_amount,
            'package_time_duration' => $request->package_time_duration,
            'package_img'           => $packagePhoto,
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);

        // return redirect()->route('packageMaster.list')->with('success', 'Package created successfully!');
        return back()->with('success', 'Package created successfully!');
    }

    public function packageMasterUpdate(Request $request, $id)
    {

        $package = DB::table('package_master')->where('id', $id)->first();
        if (! $package) {
            return back()->with('error', 'Package not found!');
        }

        $uploadFile = function ($request, $inputName, $folder, $prefix = '') {
            if ($request->hasFile($inputName)) {
                $file     = $request->file($inputName);
                $filename = 'bg' . $prefix . '_' . date('Ymd_His') . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($folder), $filename);
                return "$folder/$filename";
            }
            return null;
        };

        $packagePhoto = $uploadFile($request, 'packagePhoto', 'userApp/assets/pg-banner', 'pk_img');

        DB::table('package_master')
            ->where('id', $id)
            ->update([
                'package_name'          => $request->package_name,
                'package_amount'        => $request->package_amount,
                'package_payout_per'    => $request->package_payout_per,
                'package_total_amount'  => $request->package_total_amount,
                'package_time_duration' => $request->package_time_duration,
                'package_img'           => $packagePhoto ?? $package->package_img,
                'updated_at'            => now(),
            ]);

        return redirect()->route('packageMaster.list', $id)->with('success', 'Package updated successfully!');
    }

    public function packageMasterEdit($id)
    {
        $editPackage = DB::table('package_master')->where('id', $id)->first();
        $packages    = DB::table('package_master')->orderBy('id', 'desc')->get();

        return view('admin.logicApp.packageMaster', compact('packages', 'editPackage'));
    }

// **************************************************

    public function allMembersList()
    {
        return view('admin.allViewTables.allMembersList');
    }

// ****************************************

// ***********************************

    public function showPlanPage($slug)
    {
        // Get plan details
        $plan = DB::table('plan_master')
            ->whereRaw('LOWER(select_plan) = ?', [strtolower($slug)])
            ->first();

        if (! $plan) {
            abort(404, 'Plan not found');
        }

        // Get current logged-in member ID from session
        $beneficiaryId = session('member_id');

        // Get income transactions for the member under the plan
        $transactions = DB::table('mlm_transactions as t')
            ->join('members as m', 't.member_id', '=', 'm.member_id')
            ->select(
                't.member_id',
                'm.name as downline_name',
                't.level',
                't.amount',
                't.plan_id'
            )
            ->where('t.beneficiary_id', $beneficiaryId)
            ->where('t.plan_id', $plan->select_plan_id)
            ->get();

        // Total income
        $totalIncome = $transactions->sum('amount');

        return view('admin.allViewTables.plans_view_menu', [
            'plan'         => $plan,
            'transactions' => $transactions,
            'totalIncome'  => $totalIncome,
        ]);
    }

// *************************************

// *************************************************

// User App All Cntoler Start *************************

    /*   public function registerUserApp(Request $request)
    {
        // Validate input
        $request->validate([
            'user_name'       => 'required|string|max:255',
            'phone_number'    => 'required|string|max:20|unique:app_users,phone_number',
            'address'         => 'nullable|string',
            'profile_picture' => 'nullable|file|mimes:jpeg,png,jpg|max:20048',
        ]);

        // Step 1: Default introducer = Company
        $companyIntroducer = DB::table('app_users')->where('phone_number', '0001112223')->first();

        $introducer = DB::table('app_users')
            ->where('phone_number', $request->introducer_number)
            ->first();

        // Step 2: Check introducer child count
        if ($introducer) {
            $childCount = DB::table('app_users')
                ->where('introducer_id', $introducer->id)
                ->count();

            // If childCount >= 10, then the company will be the introducer.
            if ($childCount >= 10) {
                $introducer = $companyIntroducer;
            }
        } else {
            // If introducer is not available, company will be the introducer
            $introducer = $companyIntroducer;
        }

        // File upload helper
        $uploadFile = function ($request, $inputName, $folder, $prefix = '') {
            if ($request->hasFile($inputName)) {
                $file     = $request->file($inputName);
                $filename = $request->phone_number . '_' . $prefix . '_' . $file->getClientOriginalName();
                $file->move(public_path("uploads/$folder"), $filename);
                return "uploads/$folder/" . $filename;
            }
            return null;
        };

        $profilePicPath = $uploadFile($request, 'profile_picture', 'qr_user', 'profile');
        $qrCodePath     = $uploadFile($request, 'upi_qr_code', 'qr_user', 'qr');

        // Insert into DB
        DB::table('app_users')->insert([
            'app_u_name'       => $request->user_name,
            'phone_number'     => $request->phone_number,
            'user_wallet'      => 0,
            'introducer_id'    => $introducer->id ?? $companyIntroducer->id,
            'introducer_phone' => $introducer->phone_number ?? $companyIntroducer->phone_number,
            'introducer_name'  => $introducer->app_u_name ?? $companyIntroducer->app_u_name,
            'user_email'       => $request->user_email,
            'password'         => Hash::make('0011'),
            'app_u_address'    => $request->user_address,
            'pin_code'         => $request->pin_code,
            'bank_name'        => $request->bank_name,
            'ifsc_code'        => $request->ifsc_code,
            'bank_account_no'  => $request->bank_account_no,
            'upi_id'           => $request->upi_id,
            'upi_qr_code'      => $profilePicPath,
            'user_pic_img'     => $qrCodePath,
            'status'           => 1,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return redirect()->route('userLogin.app')->with('success', '<h3 style="color:#fff;"> Registered Successfully.<br> Login User Name = ' . $request->phone_number . '<br>Login Password Is = 0011</h3>');
    } */

    public function registerUserApp(Request $request)
    {
        // Validate input
        $request->validate([
            'user_name'    => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:app_users,phone_number',
        ]);

        // File upload helper
        $uploadFile = function ($request, $inputName, $folder, $prefix = '') {
            if ($request->hasFile($inputName)) {
                $file     = $request->file($inputName);
                $filename = $request->phone_number . '_' . $prefix . '_' . $file->getClientOriginalName();
                $file->move(public_path("uploads/$folder"), $filename);
                return "uploads/$folder/" . $filename;
            }
            return null;
        };

        $profilePicPath = $uploadFile($request, 'profile_picture', 'qr_user', 'profile');
        $qrCodePath     = $uploadFile($request, 'upi_qr_code', 'qr_user', 'qr');

        // Insert into DB
        DB::table('app_users')->insert([
            'app_u_name'        => $request->user_name,
            'phone_number'      => $request->phone_number,
            'pan_number'        => $request->pan_number,
            'cin_no'            => $request->cin_no,
            'contact_person_no' => $request->contact_person_no,
            'password'          => Hash::make('0011'),
            'app_u_address'     => $request->user_address,
            'pin_code'          => $request->pin_code,
            'bank_name'         => $request->bank_name,
            'ifsc_code'         => $request->ifsc_code,
            'bank_account_no'   => $request->bank_account_no,
            'upi_id'            => $request->upi_id,
            'upi_qr_code'       => $profilePicPath,
            'user_pic_img'      => $qrCodePath,
            'status'            => 1,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->route('addCompany.User')->with('success', '<h3 style="color:#fff;"> Registered Successfully.<br> Login User Name = ' . $request->phone_number . '<br>Login Password Is = 0011</h3>');
    }

    public function appUsersAdminPanelList(Request $request)
    {

        $query = \DB::table('app_users')->where('id', '!=', 1);

        if ($request->has('phone') && ! empty($request->phone)) {
            $query->where('phone_number', $request->phone);
        }

        $appUsers = $query->orderBy('id', 'desc')->get();



        return view('admin.logicApp.appUsers', compact('appUsers'));

    }

//appUsersAdminPanelList=> This is view of admin panle  for 1.app-users-list-admin-panel 2. add-balance-request-list 3. withdrawal-request-list

// userAppDashboard => this funcation work is for when user add Bal to see the company Bank Detels
    public function userAppDashboard()
    {
        $actived = 1;

        $membersBankDetails = DB::table('members')
            ->where('status', $actived)
            ->orderBy('id', 'asc')
            ->get();
        // Default message
        $warningMessage = null;
        // Check if there are more than 1 active members
        if ($membersBankDetails->count() > 1) {
            $warningMessage = "More than 1 company is active. Please contact the company.";
        }

        if (request()->routeIs('addBalance.userApp')) {
            return view('userApp.userAppView.addBalance', compact('membersBankDetails', 'warningMessage'));
        }

        // return view('userApp.userAppView.dashboard', compact('appPackages'));

    }
// userAppDashboard => this funcation work is for when user add Bal to see the company Bank Detels

// userAddBalance => this funcation work is for when user add Bal and Scershoot REQ
    public function userAddBalance(Request $request)
    {
        $request->validate([
            'add_balance_amount' => 'string|max:10',
            'add_pin_bal'        => 'required|integer|min:1',
            // 'payment_screenShot' => 'nullable|file|mimes:jpeg,png,jpg|max:20048',
            'userId'             => 'required|integer',
            'userName'           => 'required|string',
            'userPhone'          => 'required|string',
        ]);

        // dd($request->cash_or_wallat);

        $uploadFile = function ($request, $inputName, $folder) {
            if ($request->hasFile($inputName)) {
                $file     = $request->file($inputName);
                $filename = Str::slug($request->userPhone) . '_' . now()->format('YmdHis') . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("uploads/$folder"), $filename);
                return "uploads/$folder/" . $filename;
            }
            return null;
        };
        // Step 1: Get the balance request by ID
        // $balanceRequest = DB::table('user_balance_request')->where('id', $id)->first();

        // $cashOrWallat = $request->cash_or_wallat ?? null;
        $cashOrWallat = ($request->cash_or_wallat && $request->cash_or_wallat != 1) ? $request->cash_or_wallat : null;

        $rowStatus  = $cashOrWallat ? 1 : 2;              // If value exists, status = 1; else = 2
        $rowStatusT = $cashOrWallat ? 'Done' : 'Pending'; // If value exists, status = 1; else = 2

        // Step 6: Generate unique PINs
        $pinList       = [];
        $generatedPins = [];

        for ($i = 0; $i < $request->add_pin_bal; $i++) {
            // Generate a unique PIN string composed of:
            // - 6 random uppercase alphanumeric characters (using Laravel's Str::random)
            // - Current loop index (i + 1) to ensure uniqueness even within the same request
            // - User ID who requested the pin generation ($request->userId)
            // - Related balance request ID ($balanceRequestId) for this only 'w'
            // - Total number of pins being added in this request ($request->add_pin_bal)
            // Example format: "A1B2C3" + "1" + "101" + "7" + "20" => A1B2C31101720
            // $randomPin = strtoupper(Str::random(6)) . ($i + 1) . $request->userId . $balanceRequestId . $request->add_pin_bal;

            // Generate until a unique pin is found
            do {
                $randomPin = strtoupper(Str::random(6)) . ($i + 1) . $request->userId . 'w' . (int) $request->add_pin_bal;
            } while (in_array($randomPin, $generatedPins));

            $generatedPins[] = $randomPin;

            $pinList[] = [
                'pin'        => $randomPin,
                'id'         => $i + 1,
                'app_user'   => $request->userId,
                // 'req_id'     => $balanceRequest->id,
                'req_id'     => 'w',
                'req_bal'    => $request->req_bal_amount,
                'total_pin'  => $request->add_pin_bal,
                'status'     => 1, // inactive = 2
                                   // 'status'     => $rowStatus, // Active = 2
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $pinJsonPut = $cashOrWallat ? json_encode($pinList) : null; // ✅ only if value is present
        DB::beginTransaction();

        try {
            $paymentScreenShot = $uploadFile($request, 'payment_screenShot', 'userPaymentScreenShot');

            // Step 1: Insert request and get its ID
            $balanceRequestId = DB::table('user_balance_request')->insertGetId([
                'app_user_id'      => $request->userId,
                'app_user_name'    => $request->userName,
                'user_phone'       => $request->userPhone,
                'add_pin_bal'      => $request->add_pin_bal,
                'pin_json'         => $pinJsonPut,
                'req_bal_amount'   => $request->add_balance_amount,
                'pay_screenshot'   => $paymentScreenShot,
                'status'           => $rowStatus,                    // ✅ dynamic based on value // Pending = 1, Activ=2
                'pin_bal_add_type' => $request->cash_or_wallat ?? 1, // Default to 1 (Cash)
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            if ($rowStatus == 1) {
                DB::table('app_users')
                    ->where('id', $request->userId)
                    ->decrement('total_withdrawal_req', $request->add_balance_amount);
            }

            // Step 4: Get current wallet (unchanged)
            $walletBefore = DB::table('app_users')->where('id', $request->userId)->value('user_wallet') ?? 0;

            // Step 5: Log the transaction
            DB::table('user_transactions')->insert([
                'app_user_id'   => $request->userId,
                'type_id'       => 1, // Add Balance
                'pin_bal'       => $request->add_pin_bal,
                'amount'        => $request->add_balance_amount,
                'wallet_before' => $walletBefore,
                'wallet_after'  => $walletBefore,
                'status'        => $rowStatusT,
                'requested_at'  => now(),
                'screenshot'    => $paymentScreenShot,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::commit();

            return redirect()->route('dashboard.app')->with('success', 'Rs.' . $request->add_balance_amount . ' balance request submitted successfully with ' . $request->add_pin_bal . ' PINs.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Balance request failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while submitting your balance request.');
        }
    }

// userAddBalance => this funcation work is for when user add Bal and Scershoot REQ

// ************************************************************

// addBalanceTrafer => this funcation work is for when user add trafer by the company bals from admin panel
    public function addBalanceTrafer(Request $request, $id)
    {
        $request->validate([
            'userBlaAdd' => 'required|numeric',
        ]);

        // Step 1: Get the balance request by ID
        $balanceRequest = DB::table('user_balance_request')->where('id', $id)->first();

        if (! $balanceRequest) {
            return back()->with('error', 'Balance request not found.');
        }

        // Step 2: Fetch the user
        $user = DB::table('app_users')->where('id', $balanceRequest->app_user_id)->first();

        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        $requestedAmount = (float) $request->userBlaAdd;
        $walletBefore    = (float) $user->user_wallet;
        $walletAfter     = $walletBefore + $requestedAmount;

        // dd($walletAfter );

        DB::beginTransaction();

        try {
            // Step 3: Update balance request status to Done
            DB::table('user_balance_request')->where('id', $id)->update([
                'status'     => 1, //active = 2 , inactive=1
                'updated_at' => now(),
            ]);

            // Step 4: Update user's wallet
            DB::table('app_users')->where('id', $user->id)->update([
                'user_wallet' => $walletAfter,
            ]);

            // Step 5: Update the transaction to Done
            DB::table('user_transactions')
                ->where('app_user_id', $user->id)
                ->where('type_id', 1) // Add Balance
                ->where('status', 'Pending')
                ->where('amount', $requestedAmount)
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->update([
                    'wallet_after' => $walletAfter,
                    'status'       => 'Done',
                    'done_at'      => now(),
                    'updated_at'   => now(),
                ]);

            // Step 6: Generate unique PINs
            $pinList       = [];
            $generatedPins = [];

            for ($i = 0; $i < $balanceRequest->add_pin_bal; $i++) {
                // Generate a unique PIN string composed of:
                // - 6 random uppercase alphanumeric characters (using Laravel's Str::random)
                // - Current loop index (i + 1) to ensure uniqueness even within the same request
                // - User ID who requested the pin generation ($request->userId)
                // - Related balance request ID ($balanceRequestId)
                // - Total number of pins being added in this request ($request->add_pin_bal)
                // Example format: "A1B2C3" + "1" + "101" + "7" + "20" => A1B2C31101720
                // $randomPin = strtoupper(Str::random(6)) . ($i + 1) . $request->userId . $balanceRequestId . $request->add_pin_bal;

                // Generate until a unique pin is found
                do {
                    $randomPin = strtoupper(Str::random(6)) . ($i + 1) . $user->id . $balanceRequest->id . (int) $balanceRequest->add_pin_bal;
                } while (in_array($randomPin, $generatedPins));

                $generatedPins[] = $randomPin;

                $pinList[] = [
                    'pin'        => $randomPin,
                    'id'         => $i + 1,
                    'app_user'   => $user->id,
                    'req_id'     => $balanceRequest->id,
                    'req_bal'    => $balanceRequest->req_bal_amount,
                    'total_pin'  => $balanceRequest->add_pin_bal,
                    'status'     => 1, // Inactive
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Step 7: Store pin list JSON into balance request
            DB::table('user_balance_request')->where('id', $id)->update([
                'pin_json' => json_encode($pinList),
            ]);

            // Step 8: Update user's total_pins count
            $newTotalPins = (int) $user->total_pins + (int) $balanceRequest->add_pin_bal;

            DB::table('app_users')->where('id', $user->id)->update([
                'total_pins' => $newTotalPins,
            ]);

            DB::commit();

            return back()->with('success', 'Balance transferred and PINs generated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transfer error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating balance and generating PINs.');
        }
    }

// addBalanceTrafer => this funcation work is for when user add trafer by the company bals from admin panel
// *************************************************************

    private function getAllPins()
    {
        $userId = Session::get('app_user_id'); // ✅ Logged in user ID

        if (! $userId) {
            return []; // No session, no pins
        }

        // Fetch requests for this user
        $allRequests = DB::table('user_balance_request')
            ->where('app_user_id', $userId)
            ->select('id', 'pin_json') // ✅ id ও নিলাম
            ->get();

        $allPins = [];

        foreach ($allRequests as $req) {
            $pins = json_decode($req->pin_json, true);
            if (is_array($pins)) {
                // ✅ প্রত্যেক pin এর সাথে req_id যোগ করো
                foreach ($pins as &$pin) {
                    $pin['req_id'] = $req->id;
                }
                $allPins = array_merge($allPins, $pins);
            }
        }

        return $allPins;
    }

    public function userPINsList()
    {
        // Fetch all pins
        $allPins  = $this->getAllPins();
        $allPins1 = $this->getAllPins();

        // Try to get only status = 1 pins first
        $filteredPins = collect($allPins)->filter(function ($pin) {
            return $pin['status'] == 1;
        })->values();

        // If no status = 1 pins found, then take status = 2 pins
        if ($filteredPins->isEmpty()) {
            $filteredPins = collect($allPins)->filter(function ($pin) {
                return $pin['status'] == 2;
            })->values();
        }

        // ✅ Count active/used pins for summary
        $status2Count = collect($allPins)->where('status', 2)->count();
        $pins         = collect($allPins)->count();

        // ✅ Pagination setup
        $page    = request('page', 1);
        $perPage = request('perPage', 20);

        // Slice current page data
        $currentPageItems = $filteredPins->slice(($page - 1) * $perPage, $perPage)->values();

        // Create paginator
        $pinsPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $filteredPins->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('userApp.userAppView.userPINsList', compact('pinsPaginator', 'status2Count', 'pins', 'allPins1'));
    }

    // activateUserPin => this funcation work is for when user activ pin BY click by button useing Ajax

    public function activateUserPin(Request $request)
    {
        $request->validate([
            'pin'    => 'required',
            'req_id' => 'required|integer',
        ]);

        $balanceRequest = DB::table('user_balance_request')->where('id', $request->req_id)->first();

        if (! $balanceRequest) {
            return response()->json(['status' => 'error', 'message' => 'Request not found']);
        }

        $pinJson    = json_decode($balanceRequest->pin_json, true);
        $pinUpdated = false;

        foreach ($pinJson as &$pinEntry) {
            if ($pinEntry['pin'] === $request->pin && $pinEntry['status'] == 1) {
                $pinEntry['status'] = 2; // Mark as active
                $pinUpdated         = true;
                break;
            }
        }

        if ($pinUpdated) {
            DB::table('user_balance_request')
                ->where('id', $request->req_id)
                ->update(['pin_json' => json_encode($pinJson)]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Pin activated successfully.',
                'pin'     => $request->pin,
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Pin already used or invalid.']);
    }

// activatePinsByCount => this funcation work is for when user activ pin BY in put box bulk submit

    public function activatePinsByCount(Request $request)
    {
        $userId = Session::get('app_user_id');

        $request->validate([
            'count' => 'required|integer|min:1',
        ]);

        $toActivate     = (int) $request->count;
        $activatedCount = 0;

        // Step 1: Get all balance requests for this user
        $balanceRequests = DB::table('user_balance_request')
            ->where('app_user_id', $userId)
            ->get();

        if ($balanceRequests->isEmpty()) {
            return back()->with('error', 'No balance requests found for this user.');
        }

        // Step 2: Loop through every row for this user
        foreach ($balanceRequests as $balanceRequest) {

            $pinJson = json_decode($balanceRequest->pin_json, true);

            // যদি json invalid হয় skip করো
            if (! is_array($pinJson) || empty($pinJson)) {
                continue;
            }

            $changed = false;

            // Step 3: Loop inside the JSON pins
            foreach ($pinJson as &$pinEntry) {
                if ($activatedCount >= $toActivate) {
                    break; // done activating
                }

                if (isset($pinEntry['status']) && $pinEntry['status'] == 1) {
                    $pinEntry['status'] = 2; // activate it
                    $activatedCount++;
                    $changed = true;
                }
            }

            // Step 4: Update only if something changed
            if ($changed) {
                DB::table('user_balance_request')
                    ->where('id', $balanceRequest->id)
                    ->update(['pin_json' => json_encode($pinJson)]);
            }

            // যদি নির্দিষ্ট সংখ্যা activate হয়ে যায়, তাহলে আর বাকি রো চেক না করো
            if ($activatedCount >= $toActivate) {
                break;
            }
        }

        // Step 5: Response
        if ($activatedCount > 0) {
            return back()->with('success', "{$activatedCount} pins activated successfully.");
        }

        return back()->with('error', 'No inactive pins found to activate.');
    }

    /*    return redirect()->route('userLogin.app')->with('success', '<h3 style="color:#fff;"> Registered Successfully.<br> Login User Name = ' . $request->phone_number . '<br>Login Password Is = 000111</h3>'); */

    // withdrawMoneyUserApp => this funcation work is for when user  send a REQ for withdraw BAL from user app
    public function withdrawMoneyUserApp(Request $request)
    {
        $request->validate([
            'withdraw_req' => 'required|numeric|min:1',
            'userId'       => 'required|integer',
            'userName'     => 'required|string|max:100',
            'userPhone'    => 'required|string|max:15',
        ]);

        $userId         = $request->userId;
        $withdrawAmount = floatval($request->withdraw_req);

        // Fetch user
        $user = DB::table('app_users')->where('id', $userId)->first();
        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        $currentWallet = floatval($user->user_wallet);

        if ($currentWallet < $withdrawAmount) {
            return back()->with('error', 'Insufficient balance for withdrawal.');
        }

        $paymentScreenshot = 0; // Default

        DB::beginTransaction();
        try {
            // 1. Deduct wallet
            /*  $newWallet = $currentWallet - $withdrawAmount;
            DB::table('app_users')->where('id', $userId)->update([
                'total_withdrawal_req' => $newWallet,
            ]); */

            // 2. Insert into withdraw request table
            DB::table('user_withdraw_request')->insert([
                'app_user_id'    => $userId,
                'app_user_name'  => $request->userName,
                'user_phone'     => $request->userPhone,
                'req_bal_amount' => $withdrawAmount,
                'pay_screenshot' => $paymentScreenshot,
                'status'         => 2, // Pending
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 3. Log user transaction (type_id 4 = Withdrawal)
            DB::table('user_transactions')->insert([
                'app_user_id'   => $userId,
                'type_id'       => 4,
                'amount'        => $withdrawAmount,
                'wallet_before' => $currentWallet,
                'wallet_after'  => $currentWallet,
                'status'        => 'Pending',
                'requested_at'  => now(),
                'done_at'       => null,
                'screenshot'    => $paymentScreenshot,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::commit();

            // Update wallet in session
            // Session::put('app_user_wallet', $newWallet);

            return back()->with('success', '₹' . number_format($withdrawAmount, 2) . ' withdrawal request submitted successfully. Please wait for approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Try again.');
        }
    }

    // withdrawalScrenshortUpload => this funcation work is for when company send BAL a user
    public function withdrawalScrenshortUpload(Request $request, $id)
    {
        $request->validate([
            'payment_screenshot' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:20480', // 20 MB max
        ]);

        $withdrawal = DB::table('user_withdraw_request')->where('id', $id)->first();

        if (! $withdrawal) {
            return back()->with('error', 'Withdrawal request not found.');
        }

        $filePath = $withdrawal->pay_screenshot;

        if ($request->hasFile('payment_screenshot')) {
            $file     = $request->file('payment_screenshot');
            $filename = 'withdraw_' . now()->format('Ymd_His') . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/withdrawalDone'), $filename);
            $filePath = 'uploads/withdrawalDone/' . $filename;
        }

        DB::beginTransaction();
        try {
            // 1. Update withdrawal request table
            DB::table('user_withdraw_request')->where('id', $id)->update([
                'pay_screenshot' => $filePath,
                'status'         => 1, // Done
                'updated_at'     => now(),
            ]);

            DB::table('app_users')
                ->where('id', $withdrawal->app_user_id)
                ->decrement('total_withdrawal_req', $withdrawal->req_bal_amount);

            // 2. Update corresponding transaction in user_transactions
            DB::table('user_transactions')
                ->where('app_user_id', $withdrawal->app_user_id)
                ->where('type_id', 4)  // Withdrawal
                ->whereNull('done_at') // Pending only
                ->orderByDesc('id')
                ->limit(1)
                ->update([
                    'screenshot' => $filePath,
                    'status'     => 'Done',
                    'done_at'    => now(),
                    'updated_at' => now(),
                ]);

            DB::commit();
            return back()->with('success', 'Screenshot uploaded and withdrawal marked as completed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong while updating. Please try again.');
        }
    }

// showPackageBuyingRequests => whne user ones buy any pakegs then this show in company dasbord URL- package-buying-request-list
    public function showPackageBuyingRequests()
    {
        $requests = DB::table('user_package_purchases as upp')
            ->leftJoin('app_users as au', 'upp.app_user_id', '=', 'au.id')
            ->leftJoin('package_master as pm', 'upp.package_id', '=', 'pm.id')
            ->select(
                'upp.id',
                'au.app_u_name as user_name',
                'au.phone_number',
                'upp.amount_paid',
                'pm.package_name',
                'upp.created_at'
            )
            ->orderBy('upp.created_at', 'desc')
            ->get();

        return view('admin.logicApp.packageBuyingRequest', compact('requests'));
    }
    // showPackageBuyingRequests => whne user ones buy any pakegs then this show in company dasbord URL- package-buying-request-list

    // allTransactionsUserApp => when user do any then it will show url- all-transactions-user-app
    public function allTransactionsUserApp(Request $request)
    {
        // $userId = auth()->user()->id; // or $request->user()->id if using auth guard
        $userId = session('app_user_id');

        $transactions = DB::table('user_transactions as ut')
            ->join('transaction_types as tt', 'ut.type_id', '=', 'tt.id')
            ->select(
                'ut.*',
                'tt.name as type'
            )
            ->where('ut.app_user_id', $userId)
            ->orderByDesc('ut.id')
            ->get();

        return view('userApp.userAppView.allTransactions', compact('transactions'));
    }

/*
Start downlinesTree**************************************************

END downlinesTree**************************************************

 */

    //***********************Admin Tree */

/*     public function adminMemberTree()
    {
        // Fetch all users once
        $members = DB::table('app_users')->get();

        // Group by introducer_id for fast lookup
        $groupedMembers = $members->groupBy('introducer_id');

        $levels = $this->buildLevels($groupedMembers, 1, 10); // start from root id=1, 10 levels
                                                              // dd($levels);

        return view('admin.member_tree', compact('levels'));
    } */

    // ==========================================
    // ADMIN MEMBER TREE VIEW
    // ==========================================

    public function adminMemberTree()
    {
        // Get all users with their business data
        $allUsers = DB::table('app_users')->get();

                                                      // Build nested tree structure
        $nestedTree = $this->buildAdminNestedTree(1); // Start from root user ID 1

        // Get total statistics
        $stats = $this->getSystemStatistics();

        return view('admin.member_tree', compact('nestedTree', 'stats', 'allUsers'));
    }

/*
    private function buildLevels($groupedMembers, $rootId, $maxLevels = 10)
    {
        $levels              = [];
        $currentLevelUserIds = [$rootId];

        for ($level = 1; $level <= $maxLevels; $level++) {
            $levelUsers       = collect();
            $nextLevelUserIds = [];

            foreach ($currentLevelUserIds as $uId) {
                if (isset($groupedMembers[$uId])) {
                    $children         = $groupedMembers[$uId];
                    $levelUsers       = $levelUsers->merge($children);
                    $nextLevelUserIds = array_merge($nextLevelUserIds, $children->pluck('id')->toArray());
                }
            }

            if ($levelUsers->isEmpty()) {
                break; // stop when no users at this level
            }

            $levels[$level]      = $levelUsers;
            $currentLevelUserIds = $nextLevelUserIds;
        }

        // ✅ Reverse and reindex levels here
        $reversedLevels = array_reverse($levels, true);
        $finalLevels    = [];
        $newLevel       = 1;

        foreach ($reversedLevels as $users) {
            $finalLevels[$newLevel++] = $users;
        }

        return $finalLevels;
    }
 */

    /**
     * Build nested tree for admin view
     */
    private function buildAdminNestedTree($rootUserId, $depth = 1, $parentPath = '')
    {
        if ($depth > 10) {
            return '';
        }

        $rootUser = DB::table('app_users')->where('id', $rootUserId)->first();

        if (! $rootUser) {
            return '';
        }

        $downlines = DB::table('app_users')
            ->where('introducer_id', $rootUserId)
            ->get();

        if ($downlines->isEmpty() && $depth > 1) {
            return '';
        }

        $html = '';

        // Root user card (only for first level)
        if ($depth === 1) {
            $rootData   = $this->getUserBusinessLevelData($rootUserId);
            $totalUsers = $this->getTotalDownlineCount($rootUserId);

            $html .= '<div class="admin-root-card mb-4">';
            $html .= $this->buildAdminUserCard($rootData, $totalUsers, true);
            $html .= '</div>';
        }

        if (! $downlines->isEmpty()) {
            $html .= '<div class="accordion admin-nested-accordion" id="admin_accordion_' . $rootUserId . '_' . $depth . '">';

            foreach ($downlines as $index => $member) {
                $memberData         = $this->getUserBusinessLevelData($member->id);
                $totalDownlineCount = $this->getTotalDownlineCount($member->id);

                $currentPath = $parentPath ? $parentPath . ' > ' . $member->app_u_name : $member->app_u_name;
                $accordionId = 'admin_collapse_' . $member->id . '_' . $depth;

                $html .= '<div class="accordion-item mb-2 level-' . $depth . '">';

                // Accordion Header
                $html .= '<h2 class="accordion-header" id="admin_heading_' . $member->id . '_' . $depth . '">';
                $html .= '<button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#' . $accordionId . '"
                            aria-expanded="false">';

                if ($totalDownlineCount > 0) {
                    $html .= '<span class="member-count-badge">👥 ' . $totalDownlineCount . '</span>';
                }

                $html .= '<span style="margin-left: 50px;">';
                $html .= '👤 <strong>' . htmlspecialchars($member->app_u_name) . '</strong> ';
                $html .= '[' . htmlspecialchars($member->phone_number) . '] ';
                $html .= '| 🏆 Level ' . $memberData['qualified_level'] . ' ';
                $html .= '| 💰 ₹' . number_format($memberData['total_business'], 0);
                $html .= '</span>';

                $html .= '</button>';
                $html .= '</h2>';

                // Accordion Body
                $html .= '<div id="' . $accordionId . '"
                            class="accordion-collapse collapse"
                            data-bs-parent="#admin_accordion_' . $rootUserId . '_' . $depth . '">';

                $html .= '<div class="accordion-body">';
                $html .= '<div class="tree-path">📍 Path: ' . htmlspecialchars($currentPath) . '</div>';
                $html .= $this->buildAdminDetailCard($memberData);

                // Recursive nested downlines
                $nestedHtml = $this->buildAdminNestedTree($member->id, $depth + 1, $currentPath);

                if (! empty($nestedHtml)) {
                    $html .= '<div class="mt-3">';
                    $html .= '<h6 class="text-primary">⬇️ Direct Downlines:</h6>';
                    $html .= $nestedHtml;
                    $html .= '</div>';
                }

                $html .= '</div>'; // accordion-body
                $html .= '</div>'; // accordion-collapse
                $html .= '</div>'; // accordion-item
            }

            $html .= '</div>'; // accordion
        }

        return $html;
    }

    /**
     * Build admin user card (detailed)
     */
    private function buildAdminUserCard($userData, $totalDownlines = 0, $isRoot = false)
    {
        $html = '<div class="admin-user-detail-card">';

        if ($isRoot) {
            $html .= '<h4 class="text-center mb-3">🏢 System Root User</h4>';
        }

        $html .= '<div class="row">';

        // Left column
        $html .= '<div class="col-md-4">';
        $html .= '<p><strong>👤 Name:</strong> ' . htmlspecialchars($userData['name']) . '</p>';
        $html .= '<p><strong>📞 Phone:</strong> ' . htmlspecialchars($userData['phone']) . '</p>';
        $html .= '<p><strong>🆔 User ID:</strong> #' . $userData['user_id'] . '</p>';
        $html .= '<p><strong>👥 Total Downlines:</strong> ' . $totalDownlines . '</p>';
        $html .= '</div>';

        // Middle column
        $html .= '<div class="col-md-4">';
        $html .= '<p><strong>💼 Self Business:</strong> ₹' . number_format($userData['self_business'], 2) . '</p>';
        $html .= '<p><strong>💰 Total Business:</strong> ₹' . number_format($userData['total_business'], 2) . '</p>';
        $html .= '<p><strong>🏆 Qualified Level:</strong> <span class="badge bg-primary">Level ' . $userData['qualified_level'] . '</span></p>';
        $html .= '<p><strong>📈 Next Level Need:</strong> ₹' . number_format($userData['business_needed'], 2) . '</p>';
        $html .= '</div>';

        // Right column
        $html .= '<div class="col-md-4">';
        $html .= '<p><strong>💵 Monthly Salary:</strong> ₹' . number_format($userData['salary'], 2) . '</p>';
        $html .= '<p><strong>📊 Top Leg:</strong> ₹' . number_format($userData['top_leg_business'], 2) . ' (' . $userData['top_leg_percentage'] . '%)</p>';

        // 40:60 Status
        if ($userData['qualified_level'] >= 4) {
            $statusClass = $userData['is_4060_compliant'] ? 'bg-success' : 'bg-danger';
            $html .= '<p><strong>🔐 40:60 Status:</strong> <span class="badge ' . $statusClass . '">' . $userData['ratio_status'] . '</span></p>';
        } else {
            $html .= '<p><strong>🔐 40:60 Status:</strong> <span class="badge bg-secondary">N/A</span></p>';
        }

        $html .= '<p><strong>🧾 Salary Eligible:</strong> ';
        $html .= $userData['salary_eligible'] === 'Yes'
            ? '<span class="badge bg-success">✅ Yes</span>'
            : '<span class="badge bg-secondary">❌ No</span>';
        $html .= '</p>';
        $html .= '</div>';

        $html .= '</div>'; // row

        // Monthly commissions (if available)
        if (isset($userData['monthly_commissions'])) {
            $comm = $userData['monthly_commissions'];
            $html .= '<hr>';
            $html .= '<div class="row text-center">';
            $html .= '<div class="col-md-4">';
            $html .= '<small class="text-muted">This Month Referral</small>';
            $html .= '<h5>₹' . number_format($comm['total_referral_income'], 0) . '</h5>';
            $html .= '</div>';
            $html .= '<div class="col-md-4">';
            $html .= '<small class="text-muted">This Month Salary</small>';
            $html .= '<h5>₹' . number_format($comm['total_salary'], 0) . '</h5>';
            $html .= '</div>';
            $html .= '<div class="col-md-4">';
            $html .= '<small class="text-muted">Total Earnings</small>';
            $html .= '<h5 class="text-success">₹' . number_format($comm['total_monthly_earnings'], 0) . '</h5>';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Build admin detail card (compact for nested items)
     */
    private function buildAdminDetailCard($userData)
    {
        $html = '<div class="user-info-card">';
        $html .= '<div class="row">';

        $html .= '<div class="col-md-6">';
        $html .= '<p class="mb-2"><strong>👤 Name:</strong> ' . htmlspecialchars($userData['name']) . '</p>';
        $html .= '<p class="mb-2"><strong>📞 Phone:</strong> ' . htmlspecialchars($userData['phone']) . '</p>';
        $html .= '<p class="mb-2"><strong>💼 Self Business:</strong> ₹' . number_format($userData['self_business'], 2) . '</p>';
        $html .= '<p class="mb-2"><strong>💰 Total Business:</strong> ₹' . number_format($userData['total_business'], 2) . '</p>';
        $html .= '</div>';

        $html .= '<div class="col-md-6">';
        $html .= '<p class="mb-2"><strong>🏆 Level:</strong> <span class="badge bg-primary">Level ' . $userData['qualified_level'] . '</span></p>';
        $html .= '<p class="mb-2"><strong>💵 Salary:</strong> ₹' . number_format($userData['salary'], 2) . '</p>';
        $html .= '<p class="mb-2"><strong>📊 Top Leg:</strong> ' . $userData['top_leg_percentage'] . '%</p>';

        if ($userData['qualified_level'] >= 4) {
            $statusBadge = $userData['is_4060_compliant']
                ? '<span class="badge bg-success">✅ Unlocked</span>'
                : '<span class="badge bg-warning">🔒 Locked</span>';
            $html .= '<p class="mb-2"><strong>🔐 40:60:</strong> ' . $statusBadge . '</p>';
        }

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Get system-wide statistics
     */
    private function getSystemStatistics()
    {
        $totalUsers  = DB::table('app_users')->count();
        $activeUsers = DB::table('app_users')->where('status', 1)->count();

        $totalBusiness = DB::table('user_package_purchases')->sum('amount_paid');

        $levelDistribution = [];
        for ($i = 0; $i <= 10; $i++) {
            $count = DB::table('user_level_status')
                ->where('status', 1)
                ->where('level_no', $i)
                ->distinct('app_user_id')
                ->count('app_user_id');
            $levelDistribution[$i] = $count;
        }

        $thisMonthStart    = Carbon::now()->startOfMonth();
        $thisMonthEarnings = DB::table('user_transactions')
            ->where('created_at', '>=', $thisMonthStart)
            ->where('status', 'Done')
            ->whereIn('type_id', [5, 6])
            ->sum('amount');

        return [
            'total_users'         => $totalUsers,
            'active_users'        => $activeUsers,
            'total_business'      => $totalBusiness,
            'level_distribution'  => $levelDistribution,
            'this_month_earnings' => $thisMonthEarnings,
        ];
    }

    /**
     * Export MLM tree to Excel
     */
    public function exportToExcel()
    {
        $allUsers = DB::table('app_users')->get();

        $exportData = [];

        foreach ($allUsers as $user) {
            $userData      = $this->getUserBusinessLevelData($user->id);
            $downlineCount = $this->getTotalDownlineCount($user->id);

            $exportData[] = [
                'User ID'             => $user->id,
                'Name'                => $user->app_u_name,
                'Phone'               => $user->phone_number,
                'Introducer ID'       => $user->introducer_id ?? 'Root',
                'Self Business'       => $userData['self_business'],
                'Total Business'      => $userData['total_business'],
                'Qualified Level'     => $userData['qualified_level'],
                'Top Leg %'           => $userData['top_leg_percentage'],
                '40:60 Status'        => $userData['ratio_status'],
                'Monthly Salary'      => $userData['salary'],
                'Salary Eligible'     => $userData['salary_eligible'],
                'Total Downlines'     => $downlineCount,
                'This Month Earnings' => $userData['monthly_commissions']['total_monthly_earnings'] ?? 0,
                'Join Date'           => $user->created_at,
            ];
        }

        // Create CSV
        $filename = 'mlm_tree_export_' . date('Y-m-d_His') . '.csv';
        $handle   = fopen('php://temp', 'r+');

        // Add headers
        fputcsv($handle, array_keys($exportData[0]));

        // Add data
        foreach ($exportData as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get detailed user report (for single user analysis)
     */
    public function getUserReport($userId)
    {
        $userData      = $this->getUserBusinessLevelData($userId);
        $downlineCount = $this->getTotalDownlineCount($userId);

        $directDownlines = DB::table('app_users')
            ->where('introducer_id', $userId)
            ->get()
            ->map(fn($member) => $this->getUserBusinessLevelData($member->id));

        $monthlyBreakdown = [];
        for ($i = 1; $i <= 12; $i++) {
            $startOfMonth = Carbon::now()->subMonths(12 - $i)->startOfMonth();
            $endOfMonth   = Carbon::now()->subMonths(12 - $i)->endOfMonth();

            $income = DB::table('user_transactions')
                ->where('app_user_id', $userId)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->where('status', 'Done')
                ->whereIn('type_id', [5, 6])
                ->sum('amount');

            $monthlyBreakdown[] = [
                'month'  => $startOfMonth->format('M Y'),
                'income' => $income,
            ];
        }

        return view('admin.user_report', compact(
            'userData',
            'downlineCount',
            'directDownlines',
            'monthlyBreakdown'
        ));
    }

    /**
     * Search users by criteria
     */
    public function searchUsers(Request $request)
    {
        $query = DB::table('app_users');

        if ($request->filled('name')) {
            $query->where('app_u_name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('phone')) {
            $query->where('phone_number', 'like', '%' . $request->phone . '%');
        }

        if ($request->filled('level')) {
            $userIds = DB::table('user_level_status')
                ->where('level_no', $request->level)
                ->where('status', 1)
                ->pluck('app_user_id');

            $query->whereIn('id', $userIds);
        }

        $users = $query->paginate(50);

        $usersWithData = $users->map(fn($user) => $this->getUserBusinessLevelData($user->id));

        return view('admin.logicApp.search_results', compact('usersWithData', 'users'));
    }

    /**
     * Get level-wise statistics
     */
    public function getLevelStatistics()
    {
        $levelStats = [];

        for ($level = 1; $level <= 10; $level++) {
            $userIds = DB::table('user_level_status')
                ->where('level_no', $level)
                ->where('status', 1)
                ->pluck('app_user_id');

            $totalBusiness   = 0;
            $totalSalaryPaid = 0;
            $compliantUsers  = 0;

            foreach ($userIds as $userId) {
                $userData = $this->getUserBusinessLevelData($userId);
                $totalBusiness += $userData['total_business'];
                $totalSalaryPaid += $userData['monthly_commissions']['total_salary'] ?? 0;

                if (! empty($userData['is_4060_compliant'])) {
                    $compliantUsers++;
                }
            }

            $levelStats[] = [
                'level'             => $level,
                'user_count'        => count($userIds),
                'total_business'    => $totalBusiness,
                'avg_business'      => count($userIds) > 0 ? $totalBusiness / count($userIds) : 0,
                'total_salary_paid' => $totalSalaryPaid,
                'compliant_users'   => $compliantUsers,
                'compliance_rate'   => count($userIds) > 0 ? ($compliantUsers / count($userIds)) * 100 : 0,
            ];
        }

        return view('admin.level_statistics', compact('levelStats'));
    }

    /**
     * Recalculate all user levels (Admin action)
     */
    public function recalculateAllLevels()
    {
        try {
            DB::beginTransaction();
            $allUsers = DB::table('app_users')->pluck('id');
            $updated  = 0;

            foreach ($allUsers as $userId) {
                $this->updateUserLevelAdmin($userId);
                $updated++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully recalculated levels for {$updated} users",
                'updated_count' => $updated,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Level recalculation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to recalculate levels: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update user level (called by recalculate)
     */
    private function updateUserLevelAdmin($userId)
    {
        $thresholds = $this->getLevelThresholds();
        $legs       = $this->getDownlineBusinessForUser($userId);

        if (empty($legs)) {
            for ($level = 1; $level <= 10; $level++) {
                DB::table('user_level_status')->updateOrInsert(
                    ['app_user_id' => $userId, 'level_no' => $level],
                    [
                        'status'              => 0,
                        'business_volume'     => 0,
                        'top_leg_business'    => 0,
                        'other_legs_business' => 0,
                        'updated_at'          => now(),
                    ]
                );
            }
            return 0;
        }

        $totalBusiness  = collect($legs)->sum('total_business');
        $topLeg         = collect($legs)->max('total_business');
        $otherBusiness  = $totalBusiness - $topLeg;
        $qualifiedLevel = 0;

        for ($level = 1; $level <= 10; $level++) {
            $required = $thresholds[$level];

            if ($level >= 4) {
                $maxTopLegAllowed = $totalBusiness * 0.60;
                $effectiveTopLeg  = min($topLeg, $maxTopLegAllowed);
                $validBusiness    = $effectiveTopLeg + $otherBusiness;
            } else {
                $validBusiness = $totalBusiness;
            }

            $isQualified = ($validBusiness >= $required);

            $existingStatus = DB::table('user_level_status')
                ->where(['app_user_id' => $userId, 'level_no' => $level])
                ->first();

            $wasUnlocked = ($existingStatus && $existingStatus->status == 1);

            if ($isQualified) {
                $qualifiedLevel = $level;

                DB::table('user_level_status')->updateOrInsert(
                    ['app_user_id' => $userId, 'level_no' => $level],
                    [
                        'status'              => 1,
                        'business_volume'     => $validBusiness,
                        'top_leg_business'    => $topLeg,
                        'other_legs_business' => $otherBusiness,
                        'updated_at'          => now(),
                    ]
                );

                if (! $wasUnlocked && $level >= 4) {
                    $this->assignSalaryForLevelAdmin($userId, $level, $validBusiness);
                }
            } else {
                DB::table('user_level_status')->updateOrInsert(
                    ['app_user_id' => $userId, 'level_no' => $level],
                    [
                        'status'              => 0,
                        'business_volume'     => $validBusiness,
                        'top_leg_business'    => $topLeg,
                        'other_legs_business' => $otherBusiness,
                        'updated_at'          => now(),
                    ]
                );
                break;
            }
        }

        return $qualifiedLevel;
    }

    /**
     * Assign salary when level unlocks
     */
    private function assignSalaryForLevelAdmin($userId, $levelNo, $businessVolume)
    {
        $config = $this->getLevelSalaryConfig();

        if (! isset($config[$levelNo])) {
            return;
        }

        [$salaryAmount, $months] = $config[$levelNo];

        $start  = Carbon::now()->startOfDay();
        $next   = $start->copy()->addMonth();
        $expiry = $start->copy()->addMonths($months);

        $salaryJson = [
            'level'                     => $levelNo,
            'salary_amount'             => $salaryAmount,
            'months_total'              => $months,
            'months_paid'               => 0,
            'business_volume_at_unlock' => $businessVolume,
            'start_date'                => $start->toDateString(),
            'next_payment_date'         => $next->toDateString(),
            'expiry_date'               => $expiry->toDateString(),
            'status'                    => 'active',
        ];

        DB::table('app_users')->where('id', $userId)->update([
            'user_salary' => json_encode($salaryJson),
            'updated_at'  => now(),
        ]);

        Log::info("Admin: Salary assigned to User #{$userId}: Level {$levelNo}");
    }

    // ****************************************************

    public function getDownlineIncome($id)
    {
        $allUserIds = $this->getAllDownlineUserIds($id);

        $downlines = DB::table('app_users as au')
            ->leftJoin('user_package_purchases as upp', 'au.id', '=', 'upp.app_user_id')
            ->whereIn('au.id', $allUserIds)
            ->select('au.app_u_name as name', 'au.phone_number as phone', 'upp.amount_paid as amount')
            ->get();

        return response()->json([
            'downlines' => $downlines,
        ]);
    }

    private function getAllDownlineUserIds($parentId)
    {
        $ids     = [];
        $directs = DB::table('app_users')->where('introducer_id', $parentId)->pluck('id');

        foreach ($directs as $id) {
            $ids[] = $id;
            $ids   = array_merge($ids, $this->getAllDownlineUserIds($id));
        }

        return $ids;
    }

    // *********************************************************************

    public function updatePassword(Request $request)
    {
        $userId    = session('app_user_id');
        $userPhone = session('app_user_phone');

        if (! $userId) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.']);
        }

        // Validate file types
        $request->validate([
            'upi_qr_code'  => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'user_pic_img' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Check password match
        if ($request->new_password !== $request->confirm_password) {
            return response()->json(['success' => false, 'message' => 'Passwords do not match.']);
        }

        // Upload helper
        $uploadFile = function ($field, $folder, $prefix = '') use ($request) {
            if ($request->hasFile($field)) {
                $file     = $request->file($field);
                $filename = 'USER_' . $prefix . '_' . time() . '_' . $file->getClientOriginalName();
                $file->move(public_path("uploads/$folder"), $filename);
                return "uploads/$folder/" . $filename;
            }
            return null;
        };

        // Upload files if present
        $qrPath  = $uploadFile('upi_qr_code', 'qr_user', 'qr');
        $picPath = $uploadFile('user_pic_img', 'user_pics', 'pic');

        // Prepare update data
        $updateData = [
            // 'password' => Hash::make($request->new_password),
            'bank_name'       => $request->bank_name,
            'ifsc_code'       => $request->ifsc_code,
            'bank_account_no' => $request->bank_account_no,
            'upi_id'          => $request->upi_id,
            'updated_at'      => now(),
        ];

        // Check if new password is provided
        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($request->new_password);
        }

        if ($qrPath) {
            $updateData['upi_qr_code'] = $qrPath;
        }

        if ($picPath) {
            $updateData['user_pic_img'] = $picPath;
        }

        // Update user
        DB::table('app_users')->where('id', $userId)->update($updateData);

        return response()->json([
            'success'          => true,
            'message'          => 'Password & bank details updated successfully.',
            'redirect'         => true,
            'redirect_url'     => route('userLogin.app'),
            'password_message' => '<h3 style="color:#fff;">Your Login Id-' . $userPhone . ' and new password is: <strong>' . $request->new_password . '</strong><br>Save it carefully.</h3>',
        ]);
    }

    public function getUserData()
    {
        $userId = session('app_user_id');
        $user   = DB::table('app_users')->where('id', $userId)->first();

        return response()->json($user);
    }

    public function adminLoginAsUser($userId)
    {
        $user = DB::table('app_users')->where('id', $userId)->first();

        if (! $user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Set session variables to simulate login
        session([
            'app_user_id'     => $user->id,
            'app_user_name'   => $user->app_u_name,
            'app_user_wallet' => $user->user_wallet,
            'app_user_phone'  => $user->phone_number,
        ]);

        return redirect()->route('user.dashboard')->with('success', '🔑 You are now logged in as: ' . $user->app_u_name);
    }

    // buyPackage => this funcation work is for when company send BAL a user

    // ==========================================
    // LEVEL CONFIGURATION
    // ==========================================

    private function getLevelThresholds(): array
    {
        return [
            1  => 5000,
            2  => 25000,
            3  => 125000,
            4  => 500000,
            5  => 1500000,
            6  => 5000000,
            7  => 17500000,
            8  => 60000000,
            9  => 200000000,
            10 => 750000000,
        ];
    }

    private function getLevelPercents(): array
    {
        return [
            1  => 2.00,
            2  => 1.50,
            3  => 1.00,
            4  => 0.75,
            5  => 0.50,
            6  => 0.25,
            7  => 0.25,
            8  => 0.25,
            9  => 0.25,
            10 => 0.25,
        ];
    }

    private function getLevelSalaryConfig(): array
    {
        return [
            4  => [3500.00, 3],
            5  => [7000.00, 3],
            6  => [17500.00, 3],
            7  => [42000.00, 3],
            8  => [105000.00, 3],
            9  => [350000.00, 3],
            10 => [1400000.00, 3],
        ];
    }

    // ==========================================
    // PACKAGE PURCHASE
    // ==========================================

    public function buyPackage(Request $request)
    {
        $userId           = Session::get('app_user_id');
        $introducer_id    = Session::get('introducer_id');
        $introducer_phone = Session::get('introducer_phone');

        if (! $userId) {
            return redirect()->route('userLogin.app')->with('error', 'You must be logged in to buy a package.');
        }

        $packageId = $request->package_id;

        DB::beginTransaction();
        try {
            $user    = DB::table('app_users')->where('id', $userId)->lockForUpdate()->first();
            $package = DB::table('package_master')->where('id', $packageId)->first();

            if (! $user || ! $package) {
                DB::rollBack();
                return back()->with('error', 'User or Package not found.');
            }

            $walletBefore  = (float) $user->pin_active_bal;
            $packageAmount = round((float) ($package->package_amount ?? $package->package_total_amount ?? 0), 2);

            if ($walletBefore < $packageAmount) {
                DB::rollBack();
                return back()->with('error', 'Insufficient wallet balance.');
            }

            $affected = DB::table('app_users')
                ->where('id', $userId)
                ->where('pin_active_bal', '>=', $packageAmount)
                ->decrement('pin_active_bal', $packageAmount);

            if ($affected === 0) {
                DB::rollBack();
                return back()->with('error', 'Balance update failed.');
            }

            // 103% logic (2.03x return)
            $pakeg103 = round($packageAmount * 2.03, 2);
            DB::table('app_users')->where('id', $userId)->increment('total_pakeg_amount', $pakeg103);
            DB::table('app_users')->where('id', $userId)->increment('life_time_eran', $pakeg103);

            $walletAfter = (float) DB::table('app_users')->where('id', $userId)->value('pin_active_bal');

            // Record purchase
            $purchaseId = DB::table('user_package_purchases')->insertGetId([
                'app_user_id'      => $userId,
                'package_id'       => $packageId,
                'introducer_id'    => $introducer_id,
                'introducer_phone' => $introducer_phone,
                'amount_paid'      => $packageAmount,
                'is_credited'      => 0,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // Transaction log
            DB::table('user_transactions')->insert([
                'app_user_id'   => $userId,
                'type_id'       => 2,
                'amount'        => $packageAmount,
                'wallet_before' => $walletBefore,
                'wallet_after'  => $walletAfter,
                'status'        => 'Done',
                'requested_at'  => now(),
                'done_at'       => now(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // 🔥 Update all upline levels and distribute income
            $this->updateAllUplineLevels($userId);
            $this->distributeLevelIncome($userId, $packageAmount, $purchaseId);

            DB::table('app_users')->where('id', $userId)->update(['updated_at' => now()]);
            Session::put('app_user_wallet', $walletAfter);

            DB::commit();
            return back()->with('success', 'Package purchased successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('buyPackage error: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // ==========================================
    // UPDATE ALL UPLINE LEVELS (CRITICAL)
    // ==========================================

    private function updateAllUplineLevels($startUserId)
    {
        $currentUserId  = $startUserId;
        $processedUsers = [];

        // Traverse up the tree and update each upline
        while ($currentUserId) {
            $introducerId = DB::table('app_users')
                ->where('id', $currentUserId)
                ->value('introducer_id');

            if (! $introducerId || in_array($introducerId, $processedUsers)) {
                break;
            }

            // Update this upline's level
            $this->updateUserLevel($introducerId);

            $processedUsers[] = $introducerId;
            $currentUserId    = $introducerId;
        }
    }

    // ==========================================
    // UPDATE USER LEVEL (WITH 40:60 RULE)
    // ==========================================

    private function updateUserLevel($userId)
    {
        $thresholds = $this->getLevelThresholds();
        $legs       = $this->getDownlineBusinessForUser($userId);

        if (empty($legs)) {
            // No downlines - stays at level 0
            for ($level = 1; $level <= 10; $level++) {
                DB::table('user_level_status')->updateOrInsert(
                    ['app_user_id' => $userId, 'level_no' => $level],
                    [
                        'status'              => 0,
                        'business_volume'     => 0,
                        'top_leg_business'    => 0,
                        'other_legs_business' => 0,
                        'updated_at'          => now(),
                    ]
                );
            }
            return 0;
        }

        $totalBusiness = collect($legs)->sum('total_business');
        $topLeg        = collect($legs)->max('total_business');
        $otherBusiness = $totalBusiness - $topLeg;

        $qualifiedLevel = 0;

        // Check each level sequentially
        for ($level = 1; $level <= 10; $level++) {
            $required = $thresholds[$level];

            if ($level >= 4) {
                // 40:60 Rule: Max 60% from top leg, min 40% from other legs
                $maxTopLegAllowed = $totalBusiness * 0.60;
                $effectiveTopLeg  = min($topLeg, $maxTopLegAllowed);
                $validBusiness    = $effectiveTopLeg + $otherBusiness;
            } else {
                $validBusiness = $totalBusiness;
            }

            $isQualified = ($validBusiness >= $required);

            // Check if this level was already unlocked
            $existingStatus = DB::table('user_level_status')
                ->where(['app_user_id' => $userId, 'level_no' => $level])
                ->first();

            $wasUnlocked = ($existingStatus && $existingStatus->status == 1);

            if ($isQualified) {
                $qualifiedLevel = $level;

                DB::table('user_level_status')->updateOrInsert(
                    ['app_user_id' => $userId, 'level_no' => $level],
                    [
                        'status'              => 1,
                        'business_volume'     => $validBusiness,
                        'top_leg_business'    => $topLeg,
                        'other_legs_business' => $otherBusiness,
                        'updated_at'          => now(),
                    ]
                );

                // Assign salary only on FIRST unlock
                if (! $wasUnlocked && $level >= 4) {
                    $this->assignSalaryForLevel($userId, $level, $validBusiness);
                }
            } else {
                // Not qualified - update status
                DB::table('user_level_status')->updateOrInsert(
                    ['app_user_id' => $userId, 'level_no' => $level],
                    [
                        'status'              => 0,
                        'business_volume'     => $validBusiness,
                        'top_leg_business'    => $topLeg,
                        'other_legs_business' => $otherBusiness,
                        'updated_at'          => now(),
                    ]
                );

                // Can't qualify for higher levels if this one fails
                break;
            }
        }

        return $qualifiedLevel;
    }

    // ==========================================
    // DISTRIBUTE LEVEL INCOME
    // ==========================================

    private function distributeLevelIncome($buyerId, $packageAmount, $purchaseId = null)
    {
        $levelPercents = $this->getLevelPercents();
        $currentUserId = $buyerId;

        for ($level = 1; $level <= 10; $level++) {
            $introducerId = DB::table('app_users')
                ->where('id', $currentUserId)
                ->value('introducer_id');

            if (! $introducerId) {
                break; // No more uplines
            }

            $introducer = DB::table('app_users')
                ->where('id', $introducerId)
                ->lockForUpdate()
                ->first();

            if (! $introducer) {
                $currentUserId = $introducerId;
                continue;
            }

            // Check if this introducer has qualified for this level
            $qualifiedLevel = $this->getUserQualifiedLevel($introducer->id);

            // Only give income if introducer has qualified for this level or higher
            if ($qualifiedLevel >= $level) {
                $percent      = $levelPercents[$level] ?? 0;
                $incomeAmount = round(($packageAmount * $percent) / 100, 2);

                if ($incomeAmount > 0) {
                    $refWalletBefore = (float) $introducer->referral_income;

                    DB::table('app_users')->where('id', $introducer->id)->update([
                        'referral_income' => DB::raw("referral_income + {$incomeAmount}"),
                        'life_time_eran' => DB::raw("life_time_eran + {$incomeAmount}"),
                        'updated_at' => now(),
                    ]);

                    $refWalletAfter = $refWalletBefore + $incomeAmount;

                    DB::table('user_transactions')->insert([
                        'app_user_id'   => $introducer->id,
                        'type_id'       => 5,
                        'amount'        => $incomeAmount,
                        'wallet_before' => $refWalletBefore,
                        'wallet_after'  => $refWalletAfter,
                        'status'        => 'Done',
                        'description'   => "Level {$level} income from User #{$buyerId} (Package #{$purchaseId})",
                        'requested_at' => now(),
                        'done_at'      => now(),
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);

                    Log::info("Level {$level} income: ₹{$incomeAmount} credited to User #{$introducer->id} from User #{$buyerId}");
                }
            } else {
                Log::info("Level {$level} income SKIPPED for User #{$introducer->id} (Qualified Level: {$qualifiedLevel})");
            }

            $currentUserId = $introducer->id;
        }
    }

    // ==========================================
    // GET USER'S QUALIFIED LEVEL
    // ==========================================

    private function getUserQualifiedLevel($userId)
    {
        $maxLevel = DB::table('user_level_status')
            ->where('app_user_id', $userId)
            ->where('status', 1)
            ->max('level_no');

        // return $maxLevel ?? 0;
        return $maxLevel ?? 1;
    }
    // ==========================================
    // GET DOWNLINE BUSINESS FOR USER
    // ==========================================

    private function getDownlineBusinessForUser($userId)
    {
        $directDownlines = DB::table('app_users')
            ->where('introducer_id', $userId)
            ->get(['id']);

        $result = [];

        foreach ($directDownlines as $downline) {
            $business = $this->calculateRecursiveBusiness($downline->id);
            $result[] = [
                'downline_id'    => $downline->id,
                'total_business' => $business,
            ];
        }

        return $result;
    }

    // ==========================================
    // CALCULATE RECURSIVE BUSINESS (WITH CACHE)
    // ==========================================

    private function calculateRecursiveBusiness($userId)
    {
        static $cache = [];

        if (isset($cache[$userId])) {
            return $cache[$userId];
        }

        // Self business
        $selfBusiness = (float) DB::table('user_package_purchases')
            ->where('app_user_id', $userId)
            ->sum('amount_paid');

        // Get all downlines
        $downlines = DB::table('app_users')
            ->where('introducer_id', $userId)
            ->pluck('id');

        $total = $selfBusiness;

        foreach ($downlines as $downId) {
            $total += $this->calculateRecursiveBusiness($downId);
        }

        $cache[$userId] = $total;
        return $total;
    }

    // ==========================================
    // ASSIGN SALARY FOR LEVEL
    // ==========================================

    private function assignSalaryForLevel($userId, $levelNo, $businessVolume)
    {
        $config = $this->getLevelSalaryConfig();

        if (! isset($config[$levelNo])) {
            return; // No salary for this level
        }

        [$salaryAmount, $months] = $config[$levelNo];

        $start  = Carbon::now()->startOfDay();
        $next   = $start->copy()->addMonth();
        $expiry = $start->copy()->addMonths($months);

        $salaryJson = [
            'level'                     => $levelNo,
            'salary_amount'             => $salaryAmount,
            'months_total'              => $months,
            'months_paid'               => 0,
            'business_volume_at_unlock' => $businessVolume,
            'start_date'                => $start->toDateString(),
            'next_payment_date'         => $next->toDateString(),
            'expiry_date'               => $expiry->toDateString(),
            'status'                    => 'active',
        ];

        DB::table('app_users')->where('id', $userId)->update([
            'user_salary' => json_encode($salaryJson),
            'updated_at'  => now(),
        ]);

        Log::info("Salary assigned to User #{$userId}: Level {$levelNo}, ₹{$salaryAmount}/month for {$months} months");
    }

    // ==========================================
    // PROCESS MONTHLY SALARIES (CRON JOB)
    // ==========================================

    public function processMonthlyPayments()
    {
        $users = DB::table('app_users')
            ->whereNotNull('user_salary')
            ->get();

        $today     = Carbon::now()->startOfDay();
        $processed = 0;

        foreach ($users as $user) {
            $salary = json_decode($user->user_salary, true);

            if (! $salary || $salary['status'] !== 'active') {
                continue;
            }

            $nextPaymentDate = Carbon::parse($salary['next_payment_date'])->startOfDay();

            if ($today->gte($nextPaymentDate) && $salary['months_paid'] < $salary['months_total']) {
                DB::beginTransaction();
                try {
                    $salaryAmount = $salary['salary_amount'];
                    $walletBefore = (float) $user->referral_income;

                    // Credit salary
                    DB::table('app_users')->where('id', $user->id)->update([
                        'referral_income' => DB::raw("referral_income + {$salaryAmount}"),
                        'life_time_eran' => DB::raw("life_time_eran + {$salaryAmount}"),
                        'updated_at' => now(),
                    ]);

                    $walletAfter = $walletBefore + $salaryAmount;

                    // Log transaction
                    DB::table('user_transactions')->insert([
                        'app_user_id'   => $user->id,
                        'type_id'       => 6, // Salary type
                        'amount'        => $salaryAmount,
                        'wallet_before' => $walletBefore,
                        'wallet_after'  => $walletAfter,
                        'status'        => 'Done',
                        'description'   => "Monthly Salary - Level {$salary['level']}",
                        'requested_at' => now(),
                        'done_at'      => now(),
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);

                    // Update salary JSON
                    $salary['months_paid']++;
                    $salary['next_payment_date'] = $nextPaymentDate->copy()->addMonth()->toDateString();

                    if ($salary['months_paid'] >= $salary['months_total']) {
                        $salary['status'] = 'completed';
                    }

                    DB::table('app_users')->where('id', $user->id)->update([
                        'user_salary' => json_encode($salary),
                        'updated_at'  => now(),
                    ]);

                    DB::commit();
                    $processed++;

                    Log::info("Salary paid to User #{$user->id}: ₹{$salaryAmount}");

                } catch (\Throwable $e) {
                    DB::rollBack();
                    Log::error("Salary payment failed for User #{$user->id}: " . $e->getMessage());
                }
            }
        }

        return response()->json([
            'success'   => true,
            'processed' => $processed,
            'message'   => "Processed {$processed} salary payments.",
        ]);
    }

    // ==========================================
    // DASHBOARD - DOWNLINES TREE VIEW
    // ==========================================

    /**
     * Display nested downline tree with recursive accordions
     */
    public function downlinesTree()
    {
        $userId = Session::get('app_user_id');

        if (! $userId) {
            return redirect()->route('userLogin.app')->with('error', 'Please login first');
        }

        // Get user details
        $user = DB::table('app_users')->where('id', $userId)->first();

        if (! $user) {
            return redirect()->route('userLogin.app')->with('error', 'User not found');
        }

        // Get user's own business data
        $userBusinessData = $this->getUserBusinessLevelData($userId);

        // Generate nested accordion HTML
        $nestedDownlines = $this->buildNestedAccordion($userId, $user->app_u_name, 1);

        return view('userApp.userAppView.downlinesTree', compact(
            'user',
            'userBusinessData',
            'nestedDownlines'
        ));
    }

    /**
     * Build nested accordion HTML recursively
     *
     * @param int $userId - Current user ID
     * @param string $userName - User name for tree path
     * @param int $depth - Current depth level
     * @param string $parentPath - Tree path (e.g., "Pritam1 > B1 > H1")
     * @return string HTML
     */

    private function buildNestedAccordion($userId, $userName, $depth = 1, $parentPath = '')
    {
        // Maximum depth to prevent infinite loops
        if ($depth > 10) {
            return '';
        }

        // Get direct downlines
        $downlines = DB::table('app_users')
            ->where('introducer_id', $userId)
            ->get();

        if ($downlines->isEmpty()) {
            return '<div class="alert alert-secondary mb-2">No downlines</div>';
        }

        $html = '<div class="accordion nested-accordion" id="accordion_' . $userId . '_' . $depth . '">';

        foreach ($downlines as $index => $member) {
            $memberData = $this->getUserBusinessLevelData($member->id);

            // Get count of all nested downlines
            $totalDownlineCount = $this->getTotalDownlineCount($member->id);

            // Build tree path
            $currentPath = $parentPath ? $parentPath . ' > ' . $member->app_u_name : $member->app_u_name;

            // Unique ID for accordion
            $accordionId = 'collapse_' . $member->id . '_' . $depth;

            $html .= '<div class="accordion-item mb-2 level-' . $depth . '">';

            // Accordion Header
            $html .= '<h2 class="accordion-header" id="heading_' . $member->id . '_' . $depth . '">';
            $html .= '<button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#' . $accordionId . '"
                        aria-expanded="false"
                        aria-controls="' . $accordionId . '">';

            // Member count badge (top-left)
            if ($totalDownlineCount > 0) {
                $html .= '<span class="member-count-badge">👥 ' . $totalDownlineCount . '</span>';
            }

            $html .= '<span style="margin-left: 50px;">';
            $html .= '👤 <strong>' . htmlspecialchars($member->app_u_name) . '</strong> ';
            $html .= '[' . htmlspecialchars($member->phone_number) . '] ';
            $html .= '| 🏆 Level ' . $memberData['qualified_level'] . ' ';
            $html .= '| 💰 ₹' . number_format($memberData['total_business'], 0);
            $html .= '</span>';

            $html .= '</button>';
            $html .= '</h2>';

            // Accordion Body
            $html .= '<div id="' . $accordionId . '"
                        class="accordion-collapse collapse"
                        aria-labelledby="heading_' . $member->id . '_' . $depth . '"
                        data-bs-parent="#accordion_' . $userId . '_' . $depth . '">';

            $html .= '<div class="accordion-body">';

            // Tree Path
            $html .= '<div class="tree-path">📍 Path: ' . htmlspecialchars($currentPath) . '</div>';

            // User Details Card
            $html .= $this->buildUserInfoCard($memberData);

            // Recursive call for nested downlines
            $nestedHtml = $this->buildNestedAccordion(
                $member->id,
                $member->app_u_name,
                $depth + 1,
                $currentPath
            );

            if (! empty($nestedHtml) && trim($nestedHtml) !== '<div class="alert alert-secondary mb-2">No downlines</div>') {
                $html .= '<div class="mt-3">';
                $html .= '<h6 class="text-primary">⬇️ Direct Downlines:</h6>';
                $html .= $nestedHtml;
                $html .= '</div>';
            }

            $html .= '</div>'; // accordion-body
            $html .= '</div>'; // accordion-collapse
            $html .= '</div>'; // accordion-item
        }

        $html .= '</div>'; // accordion

        return $html;
    }

    /**
     * Build user info card HTML
     */
    private function buildUserInfoCard($memberData)
    {
        $salaryEligible = $memberData['salary_eligible'] === 'Yes'
            ? '<span class="badge bg-success">✅ Eligible</span>'
            : '<span class="badge bg-secondary">❌ Not Eligible</span>';

        // 40:60 status based on level
        if ($memberData['qualified_level'] >= 4) {
            $unlockStatus = $memberData['is_4060_compliant']
                ? '<span class="badge bg-success">✅ Unlocked</span>'
                : '<span class="badge bg-warning text-dark">🔒 Locked</span>';
        } else {
            $unlockStatus = '<span class="badge bg-secondary">N/A (Level ' . $memberData['qualified_level'] . ')</span>';
        }

        $html = '<div class="user-info-card">';
        $html .= '<div class="row">';

        // Left Column
        $html .= '<div class="col-md-6">';
        $html .= '<p class="mb-2"><strong>👤 Name:</strong> ' . htmlspecialchars($memberData['name']) . '</p>';
        $html .= '<p class="mb-2">
            <strong>📞 Phone:</strong>
            <span class="text-primary openIncomeModal"
                  data-user-name="' . htmlspecialchars($memberData['name']) . '"
                  data-user-phone="' . htmlspecialchars($memberData['phone']) . '"
                  data-user-id="' . $memberData['user_id'] . '"
                  data-bs-toggle="modal"
                  data-bs-target="#incomeDetailModal"
                  style="cursor: pointer; text-decoration: underline;">
                ' . htmlspecialchars($memberData['phone']) . '
            </span>
        </p>';
        $html .= '<p class="mb-2"><strong>💼 Self Business:</strong> ₹' . number_format($memberData['self_business'], 2) . '</p>';
        $html .= '<p class="mb-2"><strong>💰 Total Business:</strong> ₹' . number_format($memberData['total_business'], 2) . '</p>';
        $html .= '<p class="mb-2"><strong>🏆 Qualified Level:</strong> <span class="badge bg-primary">Level ' . $memberData['qualified_level'] . '</span></p>';
        $html .= '</div>';

        // Right Column
        $html .= '<div class="col-md-6">';
        $html .= '<p class="mb-2"><strong>💵 Monthly Salary:</strong> ₹' . number_format($memberData['salary'], 2) . ' (' . $memberData['salary_months'] . ' Mo.)</p>';
        $html .= '<p class="mb-2"><strong>📈 Next Level Need:</strong> ₹' . number_format($memberData['business_needed'], 2) . '</p>';
        $html .= '<p class="mb-2"><strong>📊 Top Leg:</strong> ₹' . number_format($memberData['top_leg_business'], 2) . ' (' . $memberData['top_leg_percentage'] . '%)</p>';
        $html .= '<p class="mb-2"><strong>🧾 Salary Eligible:</strong> ' . $salaryEligible . '</p>';
        $html .= '<p class="mb-2"><strong>🔐 40:60 Status:</strong> ' . $unlockStatus . '</p>';
        $html .= '</div>';

        $html .= '</div>'; // row
        $html .= '</div>'; // user-info-card

        return $html;
    }

    /**
     * Get total count of all nested downlines
     */

    private function getTotalDownlineCount($userId)
    {
        static $cache = [];

        if (isset($cache[$userId])) {
            return $cache[$userId];
        }

        $directCount = DB::table('app_users')
            ->where('introducer_id', $userId)
            ->count();

        if ($directCount === 0) {
            $cache[$userId] = 0;
            return 0;
        }

        $total = $directCount;

        $children = DB::table('app_users')
            ->where('introducer_id', $userId)
            ->pluck('id');

        foreach ($children as $childId) {
            $total += $this->getTotalDownlineCount($childId);
        }

        $cache[$userId] = $total;
        return $total;
    }

    // ==========================================
    // GET MEMBERS BY LEVEL
    // ==========================================

    private function getMembersByLevel($userId, $targetLevel)
    {
        if ($targetLevel < 1 || $targetLevel > 10) {
            return [];
        }

        $currentLevelUsers = [$userId];

        // Traverse down to target level
        for ($level = 1; $level <= $targetLevel; $level++) {
            if (empty($currentLevelUsers)) {
                return [];
            }

            $nextLevelUsers = DB::table('app_users')
                ->whereIn('introducer_id', $currentLevelUsers)
                ->pluck('id')
                ->toArray();

            if ($level == $targetLevel) {
                // Return detailed data for target level
                $members = DB::table('app_users')
                    ->whereIn('id', $nextLevelUsers)
                    ->get();

                $result = [];
                foreach ($members as $member) {
                    $result[] = $this->getUserBusinessLevelData($member->id);
                }
                return $result;
            }

            $currentLevelUsers = $nextLevelUsers;
        }

        return [];
    }

    // ==========================================
    // GET USER BUSINESS & LEVEL DATA
    // ==========================================

    private function getUserBusinessLevelData($userId)
    {
        $user = DB::table('app_users')->where('id', $userId)->first();

        if (! $user) {
            return null;
        }

        // Calculate self business
        $selfBusiness = (float) DB::table('user_package_purchases')
            ->where('app_user_id', $userId)
            ->sum('amount_paid');

        // Calculate total business (recursive)
        $totalBusiness = $this->calculateRecursiveBusiness($userId);

        // Get qualified level from database
        $qualifiedLevel = $this->getUserQualifiedLevel($userId);

        // Level thresholds
        $thresholds   = $this->getLevelThresholds();
        $salaryConfig = $this->getLevelSalaryConfig();

        // Calculate next level requirement
        $nextLevel         = $qualifiedLevel + 1;
        $nextLevelBusiness = ($nextLevel <= 10) ? $thresholds[$nextLevel] : null;
        $businessNeeded    = $nextLevelBusiness ? max(0, $nextLevelBusiness - $totalBusiness) : 0;

        // Get leg distribution
        $legs             = $this->getDownlineBusinessForUser($userId);
        $topLeg           = ! empty($legs) ? collect($legs)->max('total_business') : 0;
        $topLegPercentage = $totalBusiness > 0 ? round(($topLeg / $totalBusiness) * 100, 2) : 0;

        // 40:60 Rule ONLY applies from Level 4 onwards
        if ($qualifiedLevel >= 4) {
            $is4060Compliant = ($topLegPercentage <= 60);
        } else {
            // Level 1-3: Always compliant (rule doesn't apply)
            $is4060Compliant = true;
        }

        // Salary information
        $salaryInfo = null;
        if ($user->user_salary) {
            $salaryInfo = json_decode($user->user_salary, true);
        }

        $currentSalary  = 0;
        $salaryMonths   = 0;
        $salaryEligible = 'No';

        if ($qualifiedLevel >= 4 && isset($salaryConfig[$qualifiedLevel])) {
            [$amount, $months] = $salaryConfig[$qualifiedLevel];
            $currentSalary     = $amount;
            $salaryMonths      = $months;

            if ($salaryInfo && isset($salaryInfo['status']) && $salaryInfo['status'] === 'active') {
                $salaryEligible = 'Yes';
            } else if ($salaryInfo) {
                $salaryEligible = 'Completed';
            } else {
                $salaryEligible = 'Eligible';
            }
        }

        // Determine ratio_status based on level and compliance
        if ($qualifiedLevel >= 4) {
            $ratioStatus = $is4060Compliant ? 'Unlocked' : 'Locked';
        } else {
            $ratioStatus = 'N/A'; // Not applicable for levels 1-3
        }

        // 🆕 Get monthly commission breakdown
        $monthlyCommissions = $this->getMonthlyCommissionBreakdown($userId);

        return [
            'user_id'             => $user->id,
            'name'                => $user->app_u_name,
            'phone'               => $user->phone_number,
            'self_business'       => $selfBusiness,
            'total_business'      => $totalBusiness,
            'qualified_level'     => $qualifiedLevel,
            'next_level'          => $nextLevel <= 10 ? $nextLevel : null,
            'business_needed'     => $businessNeeded,
            'salary'              => $currentSalary,
            'salary_months'       => $salaryMonths,
            'salary_eligible'     => $salaryEligible,
            'top_leg_business'    => $topLeg,
            'top_leg_percentage'  => $topLegPercentage,
            'is_4060_compliant'   => $is4060Compliant,
            'ratio_status'        => $ratioStatus,
            'salary_info'         => $salaryInfo,
            'monthly_commissions' => $monthlyCommissions,
        ];
    }

    /**
     * Get monthly commission breakdown
     */

    private function getMonthlyCommissionBreakdown($userId)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        // Get all transactions for this month
        $transactions = DB::table('user_transactions')
            ->where('app_user_id', $userId)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', 'Done')
            ->get();

        $levelPercents = $this->getLevelPercents();

        // Initialize breakdown
        $breakdown = [
            'total_referral_income' => 0,
            'total_salary'          => 0,
            'level_wise'            => [],
            'transaction_count'     => 0,
        ];

        // Initialize level-wise array
        for ($i = 1; $i <= 10; $i++) {
            $breakdown['level_wise'][$i] = [
                'amount'     => 0,
                'count'      => 0,
                'percentage' => $levelPercents[$i] ?? 0,
            ];
        }

        foreach ($transactions as $txn) {
            $breakdown['transaction_count']++;

            // Type 5 = Level Income
            if ($txn->type_id == 5) {
                $breakdown['total_referral_income'] += $txn->amount;

                // Try to extract level from description
                if (isset($txn->description) && preg_match('/Level (\d+)/', $txn->description, $matches)) {
                    $level = (int) $matches[1];
                    if ($level >= 1 && $level <= 10) {
                        $breakdown['level_wise'][$level]['amount'] += $txn->amount;
                        $breakdown['level_wise'][$level]['count']++;
                    }
                }
            }

            // Type 6 = Monthly Salary
            if ($txn->type_id == 6) {
                $breakdown['total_salary'] += $txn->amount;
            }
        }

        $breakdown['total_monthly_earnings'] = $breakdown['total_referral_income'] + $breakdown['total_salary'];
        $breakdown['month_name']             = Carbon::now()->format('F Y');

        return $breakdown;
    }

    // ==========================================
    // MLM REPORT (API/ADMIN VIEW)
    // ==========================================

    public function mlmReport(Request $request)
    {
        $users  = DB::table('app_users')->get();
        $report = [];

        foreach ($users as $user) {
            $data     = $this->getUserBusinessLevelData($user->id);
            $report[] = $data;
        }

        // Sort by total business
        usort($report, function ($a, $b) {
            return $b['total_business'] <=> $a['total_business'];
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data'    => $report,
            ]);
        }

        return view('admin.mlm-report', compact('report'));
    }

    /**
     * Get user income transaction details (for modal)
     */

    /**
     * Get user income transaction details (for modal)
     */

    public function getUserIncomeDetails($userId)
    {
        try {
            $user = DB::table('app_users')->where('id', $userId)->first();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            // Get recent transactions
            $transactions = DB::table('user_transactions')
                ->where('app_user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get()
                ->map(function ($txn) {
                    $types = [
                        1 => 'Pin Purchase',
                        2 => 'Package Purchase',
                        3 => 'Withdrawal',
                        4 => 'Wallet Top-up',
                        5 => 'Level Income',
                        6 => 'Monthly Salary',
                    ];

                    return [
                        'date'        => Carbon::parse($txn->created_at)->format('d M Y'),
                        'type'        => $types[$txn->type_id] ?? 'Unknown',
                        'amount'      => number_format($txn->amount, 2),
                        'status'      => $txn->status,
                        'description' => $txn->description ?? '',
                    ];
                });

            // Get summary
            $totalIncome = DB::table('user_transactions')
                ->where('app_user_id', $userId)
                ->where('type_id', 5) // Level income
                ->sum('amount');

            $totalSalary = DB::table('user_transactions')
                ->where('app_user_id', $userId)
                ->where('type_id', 6) // Salary
                ->sum('amount');

            $totalPackagePurchase = DB::table('user_package_purchases')
                ->where('app_user_id', $userId)
                ->sum('amount_paid');

            return response()->json([
                'success'      => true,
                'user'         => [
                    'name'  => $user->app_u_name,
                    'phone' => $user->phone_number,
                ],
                'summary'      => [
                    'total_income'           => number_format($totalIncome, 2),
                    'total_salary'           => number_format($totalSalary, 2),
                    'total_package_purchase' => number_format($totalPackagePurchase, 2),
                    'current_wallet'         => number_format($user->referral_income, 2),
                ],
                'transactions' => $transactions,
            ]);

        } catch (\Exception $e) {
            Log::error('getUserIncomeDetails error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch income details',
            ], 500);
        }
    }

    public function processPendingSalaries($singleUserId = null)
    {
        DB::beginTransaction();
        try {
            $query = DB::table('app_users')->whereNotNull('user_salary');

            if ($singleUserId) {
                $query->where('id', $singleUserId);
            }

            $users = $query->get();

            foreach ($users as $u) {
                $salaryData = json_decode($u->user_salary, true);
                if (! $salaryData) {
                    continue;
                }

                $monthsPaid  = (int) ($salaryData['months_paid'] ?? 0);
                $monthsTotal = (int) ($salaryData['months_total'] ?? 0);
                $nextPay     = isset($salaryData['next_payment_date'])
                    ? \Carbon\Carbon::parse($salaryData['next_payment_date'])
                    : null;

                if ($monthsPaid >= $monthsTotal) {
                    continue;
                }

                if ($nextPay && $nextPay->isFuture()) {
                    continue;
                }

                $amount = (float) ($salaryData['salary_amount'] ?? 0);
                if ($amount <= 0) {
                    continue;
                }

                $current      = DB::table('app_users')->where('id', $u->id)->lockForUpdate()->first();
                $walletBefore = (float) $current->total_withdrawal_req;

                DB::table('app_users')->where('id', $u->id)->update([
                    'total_withdrawal_req' => DB::raw("total_withdrawal_req + {$amount}"),
                    'life_time_eran' => DB::raw("life_time_eran + {$amount}"),
                    'updated_at' => now(),
                ]);

                $walletAfter = (float) DB::table('app_users')->where('id', $u->id)->value('total_withdrawal_req');

                DB::table('user_transactions')->insert([
                    'app_user_id'   => $u->id,
                    'type_id'       => 6,
                    'amount'        => $amount,
                    'wallet_before' => $walletBefore,
                    'wallet_after'  => $walletAfter,
                    'status'        => 'Done',
                    'requested_at'  => now(),
                    'done_at'       => now(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                $salaryData['months_paid'] = $monthsPaid + 1;
                if ($salaryData['months_paid'] >= $monthsTotal) {
                    $salaryData['next_payment_date'] = null;
                } else {
                    $next                            = ($nextPay ?: now())->addMonth();
                    $salaryData['next_payment_date'] = $next->toDateString();
                }

                DB::table('app_users')->where('id', $u->id)->update([
                    'user_salary' => json_encode($salaryData),
                    'updated_at'  => now(),
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Salary processed successfully']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // ******************************************************
    public function userAppDashboardUpdate()
    {
        $appPackages = DB::table('package_master')->get();
        $app_banners = \DB::table('app_banners')->get();
        $userId      = session('app_user_id');
        $allPins     = $this->getAllPins();

        $purchases = DB::table('user_package_purchases')
            ->join('package_master', 'user_package_purchases.package_id', '=', 'package_master.id')
            ->where('user_package_purchases.app_user_id', $userId)
            ->where('user_package_purchases.is_credited', 0)
            ->select(
                'user_package_purchases.id as purchase_id',
                'user_package_purchases.created_at',
                'package_master.package_total_amount',
                'package_master.package_time_duration'
            )
            ->get();

        foreach ($purchases as $purchase) {
            $perMinuteAmount = floatval($purchase->package_total_amount) / intval($purchase->package_time_duration);

            // How many minutes have passed?
            // $minutesPassed = \Carbon\Carbon::parse($purchase->created_at)->diffInMonths(now());
            $minutesPassed = \Carbon\Carbon::parse($purchase->created_at)->diffInMinutes(now());

            // does not exceed duration
            $minutesPassed = min($minutesPassed, intval($purchase->package_time_duration));

            // How many times has it already been credited?
            $alreadyCreditedCount = DB::table('user_transactions')
                ->where('app_user_id', $userId)
                ->where('type_id', 3) // maturity
                ->where('purchase_id', $purchase->purchase_id)
                ->count();

            // How many new installments will I have to pay?
            $newCredits = $minutesPassed - $alreadyCreditedCount;

            if ($newCredits > 0) {
                DB::beginTransaction();
                try {
                    $user = DB::table('app_users')->where('id', $userId)->lockForUpdate()->first();

                    $currentWallet       = floatval($user->total_withdrawal_req);
                    $currentPackageTotal = floatval($user->total_pakeg_amount);

                    for ($i = 0; $i < $newCredits; $i++) {
                        $amountToCredit = $perMinuteAmount;

                        $newWallet       = $currentWallet + $amountToCredit;
                        $newPackageTotal = $currentPackageTotal - $amountToCredit;

                        // 1. Update user wallet + package_total_amount থেকে মাইনাস
                        DB::table('app_users')
                            ->where('id', $userId)
                            ->update([
                                'total_withdrawal_req' => $newWallet,
                                'total_pakeg_amount'   => $newPackageTotal,
                            ]);

                        // 2. Insert transaction log
                        DB::table('user_transactions')->insert([
                            'app_user_id'   => $userId,
                            'purchase_id'   => $purchase->purchase_id,
                            'type_id'       => 3, // maturity
                            'amount'        => $amountToCredit,
                            'wallet_before' => $currentWallet,
                            'wallet_after'  => $newWallet,
                            'status'        => 'Done',
                            'requested_at'  => now(),
                            'done_at'       => now(),
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ]);

                        // update current values for next loop
                        $currentWallet       = $newWallet;
                        $currentPackageTotal = $newPackageTotal;
                    }

                    // যদি সব মিনিট শেষ হয়ে যায় → purchase complete
                    if ($minutesPassed >= intval($purchase->package_time_duration)) {
                        DB::table('user_package_purchases')
                            ->where('id', $purchase->purchase_id)
                            ->update(['is_credited' => 1, 'updated_at' => now()]);
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    // Log error if needed
                }
            }
        }

        $hasBoughtPackage1 = DB::table('user_package_purchases')
            ->where('app_user_id', $userId)
            ->where('package_id', 1)
            ->exists();

        $packageAmount = DB::table('user_package_purchases')
            ->where('app_user_id', $userId)
            ->get();

        // Refresh wallet in session
        $userWallet = DB::table('app_users')->where('id', $userId)->value('user_wallet');

        Session::put('app_user_wallet', $userWallet);

        return view('userApp.userAppView.dashboard', compact('userWallet', 'appPackages', 'hasBoughtPackage1', 'allPins', 'packageAmount', 'app_banners'));
    }

// ************************************************************

// myPackagesList => when user do any then it will show url- my-packages-list

    public function myPackagesList()
    {
        $userId = session('app_user_id');

        $transactions = DB::table('user_transactions')
            ->where('app_user_id', $userId)
            ->whereIn('type_id', [2, 3]) // Buy or Maturity
            ->orderByDesc('id')
            ->get();

        $packageData = DB::table('user_package_purchases as upp')
            ->join('package_master as pm', 'upp.package_id', '=', 'pm.id')
            ->where('upp.app_user_id', $userId)
            ->select(
                'upp.id as purchase_id',
                'upp.amount_paid',
                'upp.created_at as purchase_created_at',
                'upp.is_credited',
                'pm.package_name',
                'pm.package_amount',
                'pm.package_total_amount',
                'pm.package_time_duration',
                'pm.package_payout_per'
            )
            ->get();

        $combined = $transactions->map(function ($txn) use ($packageData) {
            $match = null;

            if ($txn->type_id == 2) { // Buy
                $match = $packageData->first(function ($pkg) use ($txn) {
                    return (float) $pkg->amount_paid === (float) $txn->amount &&
                    \Carbon\Carbon::parse($pkg->purchase_created_at)->format('Y-m-d H:i') ===
                    \Carbon\Carbon::parse($txn->requested_at)->format('Y-m-d H:i');
                });
            }

            if ($txn->type_id == 3) { // Maturity
                $match = $packageData->first(function ($pkg) use ($txn) {
                    return $pkg->is_credited == 1 &&
                    (float) $pkg->package_total_amount === (float) $txn->amount;
                });
            }

            return (object) [
                'type_id'               => $txn->type_id,
                'type_name'             => $txn->type_id == 2 ? 'Package Buy' : 'Maturity',
                'status'                => $txn->status,
                'amount'                => $txn->amount,
                'wallet_before'         => $txn->wallet_before,
                'wallet_after'          => $txn->wallet_after,
                'requested_at'          => $txn->requested_at,
                'done_at'               => $txn->done_at,
                'package_name'          => $match->package_name ?? 'N/A',
                'package_amount'        => $match->package_amount ?? null,
                'package_total_amount'  => $match->package_total_amount ?? null,
                'package_time_duration' => $match->package_time_duration ?? null,
                'package_payout_per'    => $match->package_payout_per ?? null,
                'is_credited'           => $match->is_credited ?? null,
            ];
        });

        return view('userApp.userAppView.myPackagesList', [
            'appPackages' => $combined,
        ]);
    }

    public function transferPins(Request $request)
    {
        $request->validate([
            'userId'       => 'required|integer',
            'to_phone'     => 'required',
            'withdraw_req' => 'required|integer|min:1',
        ]);

        $senderId      = $request->userId;
        $receiverPhone = $request->to_phone;
        $pinCount      = intval($request->withdraw_req);

        // ✅ Receiver user info
        $receiver = DB::table('app_users')->where('phone_number', $receiverPhone)->first();
        if (! $receiver) {
            return back()->with('error', 'Receiver not found!');
        }

        // ✅ Sender pins record
        $senderReq = DB::table('user_balance_request')->where('app_user_id', $senderId)->first();
        if (! $senderReq) {
            return back()->with('error', 'Sender does not have any PIN records!');
        }

        $pins = json_decode($senderReq->pin_json, true) ?? [];

        // ✅ Inactive pins filter (status = 1 means unused)
        $inactivePins = array_values(array_filter($pins, fn($p) => $p['status'] == 1));

        if (count($inactivePins) < $pinCount) {
            return back()->with('error', 'Not enough inactive pins!');
        }

        // ✅ Pick pins to transfer
        $transferPins = array_slice($inactivePins, 0, $pinCount);

        // ✅ Remove transferred pins from sender (pin দিয়ে match করা হচ্ছে)
        $remainingPins    = [];
        $transferPinsKeys = array_map(fn($p) => $p['pin'], $transferPins);

        foreach ($pins as $p) {
            if (! in_array($p['pin'], $transferPinsKeys)) {
                $remainingPins[] = $p;
            }
        }

        DB::table('user_balance_request')->where('app_user_id', $senderId)
            ->update(['pin_json' => json_encode($remainingPins), 'updated_at' => now()]);

        // ✅ Receiver record check
        $receiverReq = DB::table('user_balance_request')->where('app_user_id', $receiver->id)->first();

        if (! $receiverReq) {
            DB::table('user_balance_request')->insert([
                'app_user_id'   => $receiver->id,
                'app_user_name' => $receiver->app_u_name,
                'user_phone'    => $receiver->phone_number,
                'pin_json'      => '[]',
                'status'        => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
            $receiverReq = DB::table('user_balance_request')->where('app_user_id', $receiver->id)->first();
        }

        // ✅ Receiver pins update
        $receiverPins = json_decode($receiverReq->pin_json, true) ?? [];
        foreach ($transferPins as &$p) {
            $p['app_user']   = $receiver->id;
            $p['updated_at'] = now();
        }
        $receiverPins = array_merge($receiverPins, $transferPins);

        DB::table('user_balance_request')->where('app_user_id', $receiver->id)
            ->update(['pin_json' => json_encode($receiverPins), 'updated_at' => now()]);

        // ✅ Transaction log
        DB::table('user_transactions')->insert([
            'app_user_id' => $senderId,
            'type_id'     => 5, // ধরি 5 হবে 'PIN_TRANSFER'
            'amount'      => $pinCount,
            'remarks'     => 'Transferred to ' . $receiver->phone_number,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return back()->with('success', "$pinCount PIN(s) transferred successfully to {$receiver->phone_number}!");
    }

// User App All Cntoler End *************************

    public function toggleUserStatus($userId)
    {
        $user = DB::table('app_users')->where('id', $userId)->first();

        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        $newStatus = $user->status == 1 ? 0 : 1;

        DB::table('app_users')
            ->where('id', $userId)
            ->update(['status' => $newStatus]);

        $message = $newStatus == 1 ? 'User activated successfully.' : 'User deactivated successfully.';

        return redirect()->back()->with('success', $message);
    }

// ****************************************Dashbord

    public function index()
    {
        $stats = $this->getUltimateDashboardStats();

        return view('admin.logicApp.dashboard', compact('stats'));
    }

    public function uIndex()
    {
        $stats = $this->getUltimateDashboardStats();

        return view('admin.users.uDashboard', compact('stats'));
    }

    private function getUltimateDashboardStats()
    {
        $today        = Carbon::now();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfYear  = $today->copy()->startOfYear();

        // ===== CORE METRICS =====

        // Total Users & Growth
        $totalUsers    = DB::table('app_users')->count();
        $activeUsers   = DB::table('app_users')->where('status', 1)->count();
        $inactiveUsers = $totalUsers - $activeUsers;
        $todayNewUsers = DB::table('app_users')->whereDate('created_at', $today)->count();
        $monthNewUsers = DB::table('app_users')->where('created_at', '>=', $startOfMonth)->count();
        $yearNewUsers  = DB::table('app_users')->where('created_at', '>=', $startOfYear)->count();

        // Business Metrics
        $totalBusiness = DB::table('user_package_purchases')->sum('amount_paid');
        $todayBusiness = DB::table('user_package_purchases')->whereDate('created_at', $today)->sum('amount_paid');
        $monthBusiness = DB::table('user_package_purchases')->where('created_at', '>=', $startOfMonth)->sum('amount_paid');
        $yearBusiness  = DB::table('user_package_purchases')->where('created_at', '>=', $startOfYear)->sum('amount_paid');

        // Average ticket size
        $avgPackageValue = DB::table('user_package_purchases')->avg('amount_paid');
        $totalPackages   = DB::table('user_package_purchases')->count();

        // ===== WALLET & BALANCE ANALYTICS =====

        $totalWalletBalance    = DB::table('app_users')->sum('user_wallet');
        $totalPinBalance       = DB::table('app_users')->sum('pin_active_bal');
        $totalReferralIncome   = DB::table('app_users')->sum('referral_income');
        $totalLifetimeEarnings = DB::table('app_users')->sum('life_time_eran');

        // ===== WITHDRAWAL & REQUESTS =====

        $pendingWithdrawals = DB::table('user_withdraw_request')
            ->where('status', 2)
            ->sum('req_bal_amount');
        $pendingWithdrawalCount = DB::table('user_withdraw_request')->where('status', 2)->count();

        $approvedWithdrawals = DB::table('user_withdraw_request')
            ->where('status', 1)
            ->sum('req_bal_amount');
        $approvedWithdrawalCount = DB::table('user_withdraw_request')->where('status', 1)->count();

        $rejectedWithdrawals = DB::table('user_withdraw_request')
            ->where('status', 0)
            ->sum('req_bal_amount');
        $rejectedWithdrawalCount = DB::table('user_withdraw_request')->where('status', 0)->count();

        // Total withdrawal requests
        $totalWithdrawalRequests = $pendingWithdrawalCount + $approvedWithdrawalCount + $rejectedWithdrawalCount;

        // ===== PIN & BALANCE REQUESTS =====

        $pendingBalanceRequests = DB::table('user_balance_request')
            ->where('status', 2)
            ->count();
        $totalPinsRequested = DB::table('user_balance_request')->sum('add_pin_bal');

        $approvedBalanceRequests = DB::table('user_balance_request')
            ->where('status', 1)
            ->count();

        // ===== TRANSACTION ANALYTICS =====

        // By Type
        $transactionsByType = DB::table('user_transactions')
            ->select('type_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('type_id')
            ->get()
            ->keyBy('type_id');

        $addBalanceTotal  = $transactionsByType->get(1)->total ?? 0;
        $packageBuyTotal  = $transactionsByType->get(2)->total ?? 0;
        $maturityTotal    = $transactionsByType->get(3)->total ?? 0;
        $withdrawalTotal  = $transactionsByType->get(4)->total ?? 0;
        $levelIncomeTotal = $transactionsByType->get(5)->total ?? 0;
        $salaryTotal      = $transactionsByType->get(6)->total ?? 0;

        // Pending vs Done
        $pendingTransactions = DB::table('user_transactions')
            ->where('status', 'Pending')
            ->count();
        $doneTransactions = DB::table('user_transactions')
            ->where('status', 'Done')
            ->count();

        // This Month Payouts
        $monthPayouts = DB::table('user_transactions')
            ->where('created_at', '>=', $startOfMonth)
            ->where('status', 'Done')
            ->whereIn('type_id', [5, 6]) // Level income + Salary
            ->sum('amount');

        // ===== PACKAGE ANALYTICS =====

        $packageStats = DB::table('package_master')
            ->leftJoin('user_package_purchases', 'package_master.id', '=', 'user_package_purchases.package_id')
            ->select(
                'package_master.id',
                'package_master.package_name',
                'package_master.package_amount',
                DB::raw('COUNT(user_package_purchases.id) as purchase_count'),
                DB::raw('SUM(user_package_purchases.amount_paid) as total_revenue')
            )
            ->groupBy('package_master.id', 'package_master.package_name', 'package_master.package_amount')
            ->orderBy('purchase_count', 'desc')
            ->get();

        $mostPopularPackage = $packageStats->first();

        // ===== LEVEL DISTRIBUTION =====

        $levelDistribution = [];
        for ($i = 0; $i <= 10; $i++) {
            $count = DB::table('user_level_status')
                ->where('level_no', $i)
                ->where('status', 1)
                ->distinct('app_user_id')
                ->count('app_user_id');
            $levelDistribution[$i] = $count;
        }

        // Level 4+ users (eligible for salary)
        $salaryEligibleUsers = array_sum(array_slice($levelDistribution, 4));

        // ===== COMPLIANCE & VIOLATIONS =====

        $level4PlusUsers = DB::table('user_level_status')
            ->where('level_no', '>=', 4)
            ->where('status', 1)
            ->distinct('app_user_id')
            ->pluck('app_user_id');

        $compliantUsers = 0;
        $violationUsers = [];

        foreach ($level4PlusUsers as $userId) {
            $userData = $this->quickComplianceCheck($userId);
            if ($userData['is_4060_compliant']) {
                $compliantUsers++;
            } else {
                $violationUsers[] = $userData;
            }
        }

        $complianceRate = count($level4PlusUsers) > 0 ? ($compliantUsers / count($level4PlusUsers)) * 100 : 100;

        // ===== TOP PERFORMERS =====

        // Top 10 by business
        $topByBusiness = $this->getTopPerformers('business', 10);

        // Top 10 by referral income
        $topByIncome = DB::table('app_users')
            ->select('id', 'app_u_name', 'phone_number', 'referral_income')
            ->orderBy('referral_income', 'desc')
            ->limit(10)
            ->get();

        // Top 10 by team size
        $topByTeamSize = $this->getTopByTeamSize(10);

        // ===== RECENT ACTIVITIES (DETAILED) =====

        $recentActivities = DB::table('user_transactions')
            ->join('app_users', 'user_transactions.app_user_id', '=', 'app_users.id')
            ->select(
                'user_transactions.*',
                'app_users.app_u_name',
                'app_users.phone_number'
            )
            ->orderBy('user_transactions.created_at', 'desc')
            ->limit(20)
            ->get();

        // ===== HOURLY ACTIVITY (TODAY) =====

        $hourlyActivity = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourlyActivity[$hour] = DB::table('user_transactions')
                ->whereDate('created_at', $today)
                ->whereRaw('HOUR(created_at) = ?', [$hour])
                ->count();
        }

        // ===== DAILY STATS (LAST 30 DAYS) =====

        $dailyStats = [];
        for ($i = 29; $i >= 0; $i--) {
            $date         = $today->copy()->subDays($i);
            $dailyStats[] = [
                'date'         => $date->format('M d'),
                'users'        => DB::table('app_users')->whereDate('created_at', $date)->count(),
                'packages'     => DB::table('user_package_purchases')->whereDate('created_at', $date)->count(),
                'business'     => DB::table('user_package_purchases')->whereDate('created_at', $date)->sum('amount_paid'),
                'transactions' => DB::table('user_transactions')->whereDate('created_at', $date)->count(),
            ];
        }

        // ===== MONTHLY COMPARISON (LAST 12 MONTHS) =====

        $monthlyComparison = [];
        for ($i = 11; $i >= 0; $i--) {
            $month      = $today->copy()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd   = $month->copy()->endOfMonth();

            $monthlyComparison[] = [
                'month'    => $month->format('M Y'),
                'users'    => DB::table('app_users')->whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'business' => DB::table('user_package_purchases')->whereBetween('created_at', [$monthStart, $monthEnd])->sum('amount_paid'),
                'payouts'  => DB::table('user_transactions')
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->where('status', 'Done')
                    ->whereIn('type_id', [5, 6])
                    ->sum('amount'),
            ];
        }

        // ===== SYSTEM HEALTH INDICATORS =====

        $systemHealth = [
            'withdrawal_processing_time' => $this->getAvgWithdrawalTime(),
            'user_satisfaction_score'    => $this->calculateSatisfactionScore(),
            'network_growth_rate'        => $this->calculateGrowthRate(),
            'revenue_per_user'           => $totalUsers > 0 ? $totalBusiness / $totalUsers : 0,
        ];

        // ===== ALERTS & WARNINGS =====

        $alerts = $this->generateSystemAlerts([
            'pending_withdrawals'      => $pendingWithdrawalCount,
            'pending_balance_requests' => $pendingBalanceRequests,
            'compliance_rate'          => $complianceRate,
            'violation_users'          => count($violationUsers),
            'inactive_users'           => $inactiveUsers,
        ]);

        // ===== FINANCIAL SUMMARY =====

        $financialSummary = [
            'total_inflow'  => $totalBusiness,
            'total_outflow' => $approvedWithdrawals + $monthPayouts,
            'net_profit'    => $totalBusiness - ($approvedWithdrawals + $monthPayouts),
            'profit_margin' => $totalBusiness > 0 ? (($totalBusiness - ($approvedWithdrawals + $monthPayouts)) / $totalBusiness) * 100 : 0,
        ];

        // ===== RETURN FULL STATS ARRAY =====

        return [
            // Core Metrics
            'total_users'               => $totalUsers,
            'active_users'              => $activeUsers,
            'inactive_users'            => $inactiveUsers,
            'today_new_users'           => $todayNewUsers,
            'month_new_users'           => $monthNewUsers,
            'year_new_users'            => $yearNewUsers,

            // Business Metrics
            'total_business'            => $totalBusiness,
            'today_business'            => $todayBusiness,
            'month_business'            => $monthBusiness,
            'year_business'             => $yearBusiness,
            'avg_package_value'         => $avgPackageValue,
            'total_packages'            => $totalPackages,

            // Wallet & Balance
            'total_wallet_balance'      => $totalWalletBalance,
            'total_pin_balance'         => $totalPinBalance,
            'total_referral_income'     => $totalReferralIncome,
            'total_lifetime_earnings'   => $totalLifetimeEarnings,

            // Withdrawals
            'pending_withdrawals'       => $pendingWithdrawals,
            'pending_withdrawal_count'  => $pendingWithdrawalCount,
            'approved_withdrawals'      => $approvedWithdrawals,
            'approved_withdrawal_count' => $approvedWithdrawalCount,
            'rejected_withdrawals'      => $rejectedWithdrawals,
            'rejected_withdrawal_count' => $rejectedWithdrawalCount,
            'total_withdrawal_requests' => $totalWithdrawalRequests,

            // Balance Requests
            'pending_balance_requests'  => $pendingBalanceRequests,
            'approved_balance_requests' => $approvedBalanceRequests,
            'total_pins_requested'      => $totalPinsRequested,

            // Transactions
            'add_balance_total'         => $addBalanceTotal,
            'package_buy_total'         => $packageBuyTotal,
            'maturity_total'            => $maturityTotal,
            'withdrawal_total'          => $withdrawalTotal,
            'level_income_total'        => $levelIncomeTotal,
            'salary_total'              => $salaryTotal,
            'pending_transactions'      => $pendingTransactions,
            'done_transactions'         => $doneTransactions,
            'month_payouts'             => $monthPayouts,

            // Packages
            'package_stats'             => $packageStats,
            'most_popular_package'      => $mostPopularPackage,

            // Levels
            'level_distribution'        => $levelDistribution,
            'salary_eligible_users'     => $salaryEligibleUsers,

            // Compliance
            'compliance_rate'           => $complianceRate,
            'compliant_users'           => $compliantUsers,
            'violation_users'           => $violationUsers,

            // Top Performers
            'top_by_business'           => $topByBusiness,
            'top_by_income'             => $topByIncome,
            'top_by_team_size'          => $topByTeamSize,

            // Activity
            'recent_activities'         => $recentActivities,
            'hourly_activity'           => $hourlyActivity,

            // Charts Data
            'daily_stats'               => $dailyStats,
            'monthly_comparison'        => $monthlyComparison,

            // System Health
            'system_health'             => $systemHealth,

            // Alerts
            'alerts'                    => $alerts,

            // Financial Summary
            'financial_summary'         => $financialSummary,
        ];
    }

    // Helper Methods

    private function quickComplianceCheck($userId)
    {
        $legs             = $this->getDownlineBusinessForUser($userId);
        $totalBusiness    = collect($legs)->sum('total_business');
        $topLeg           = collect($legs)->max('total_business') ?? 0;
        $topLegPercentage = $totalBusiness > 0 ? ($topLeg / $totalBusiness) * 100 : 0;

        $qualifiedLevel = DB::table('user_level_status')
            ->where('app_user_id', $userId)
            ->where('status', 1)
            ->max('level_no') ?? 0;

        return [
            'user_id'            => $userId,
            'qualified_level'    => $qualifiedLevel,
            'is_4060_compliant'  => $topLegPercentage <= 60,
            'top_leg_percentage' => round($topLegPercentage, 2),
        ];
    }

    private function getTopPerformers($by = 'business', $limit = 10)
    {
        $users = DB::table('app_users')->select('id', 'app_u_name', 'phone_number')->get();

        $ranked = $users->map(function ($user) {
            return [
                'id'        => $user->id,
                'name'      => $user->app_u_name,
                'phone'     => $user->phone_number,
                'business'  => $this->calculateRecursiveBusiness($user->id),
                'team_size' => $this->getTotalDownlineCount($user->id),
            ];
        })->sortByDesc('business')->take($limit)->values();

        return $ranked;
    }

    private function getTopByTeamSize($limit = 10)
    {
        $users = DB::table('app_users')->select('id', 'app_u_name', 'phone_number')->get();

        return $users->map(function ($user) {
            return [
                'id'        => $user->id,
                'name'      => $user->app_u_name,
                'phone'     => $user->phone_number,
                'team_size' => $this->getTotalDownlineCount($user->id),
            ];
        })->sortByDesc('team_size')->take($limit)->values();
    }

    private function getAvgWithdrawalTime()
    {
        $avg = DB::table('user_withdraw_request')
            ->where('status', 1)
            ->whereNotNull('updated_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours'))
            ->first();

        return round($avg->avg_hours ?? 0, 1);
    }

    private function calculateSatisfactionScore()
    {
        $approved = DB::table('user_withdraw_request')->where('status', 1)->count();
        $total    = DB::table('user_withdraw_request')->count();

        return $total > 0 ? round(($approved / $total) * 100, 1) : 0;
    }

    private function calculateGrowthRate()
    {
        $thisMonth = DB::table('app_users')->where('created_at', '>=', Carbon::now()->startOfMonth())->count();
        $lastMonth = DB::table('app_users')
            ->whereBetween('created_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth(),
            ])
            ->count();

        return $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : 0;
    }

    private function generateSystemAlerts($data)
    {
        $alerts = [];

        if ($data['pending_withdrawals'] > 10) {
            $alerts[] = [
                'type'    => 'danger',
                'icon'    => 'exclamation-triangle',
                'title'   => 'High Pending Withdrawals',
                'message' => "{$data['pending_withdrawals']} withdrawal requests are pending. Please review immediately!",
                'action' => route('admin.withdrawals.pending'),
            ];
        }

        if ($data['pending_balance_requests'] > 5) {
            $alerts[] = [
                'type'    => 'warning',
                'icon'    => 'clock',
                'title'   => 'Balance Requests Pending',
                'message' => "{$data['pending_balance_requests']} balance requests need approval.",
                'action' => route('admin.balance.requests'),
            ];
        }

        if ($data['compliance_rate'] < 60) {
            $alerts[] = [
                'type'    => 'danger',
                'icon'    => 'shield-alt',
                'title'   => 'Low Compliance Rate',
                'message' => "Only {$data['compliance_rate']}% of Level 4+ users are compliant. {$data['violation_users']} users in violation!",
                'action' => route('admin.compliance.report'),
            ];
        }

        if ($data['inactive_users'] > ($data['inactive_users'] + 100) * 0.3) {
            $alerts[] = [
                'type'    => 'info',
                'icon'    => 'user-slash',
                'title'   => 'High Inactive Users',
                'message' => "{$data['inactive_users']} users are inactive. Consider re-engagement campaign.",
                'action' => route('admin.users.inactive'),
            ];
        }

        return $alerts;
    }

    // ===========================
// BUSINESS PLAN FUNCTIONS
// ===========================

    public function businessPlanAdd()
    {
        $userId     = Session::get('app_user_id');
        $appUser    = DB::table('app_users')->where('id', $userId)->first();
        $categories = DB::table('business_category')->get();

        return view('admin.users.businessPlanAdd', [
            'appUser'    => $appUser,
            'categories' => $categories,
            'plan'       => null,
            'isEdit'     => false,
        ]);
    }

    public function businessPlanStore(Request $request)
    {
        $userId   = Session::get('app_user_id');
        $appUser  = DB::table('app_users')->where('id', $userId)->first();
        $category = DB::table('business_category')->where('id', $request->business_category_id)->first();

        DB::table('business_plans')->insert([
            'user_by'                => $userId,                           // FK to app_users.id, From Session::get(app_user_id)
            'add_user_name'          => $appUser->app_u_name ?? 'Unknown', // Fetched from app_users table
            'business_category_id'   => $category->id,                     // ID from business_category table
            'business_category_name' => $category->category_name,          // Name from business_category table
            'loan_amount'            => $request->loan_amount,             // Loan amount requested
            'extra_amount'           => $request->extra_amount,            // Any extra amount
            'number_of_days'         => $request->number_of_days,          // Number of days for loan
            'membership_per'         => $request->membership_per,          // Membership %
            'membership_charge'      => $request->membership_charge,       // Membership charge
            'emi_amount'             => $request->emi_amount,              // EMI amount
            'processing_charge'      => $request->processing_charge,       // Processing fee
            'loan_insurance_charge'  => $request->loan_insurance_charge,   // Loan insurance charge
            'other_charges'          => $request->other_charges,           // Any other charges
            'interest_amount'        => $request->interest_amount,         // Interest amount
            'interest_rate'          => $request->interest_rate,           // Interest rate
            'final_amount'           => $request->final_amount,            // Final amount to be paid
            'status'                 => 1,                                 // 0=Inactive, 1=Active
            'created_at'             => now(),                             // Record created at
            'updated_at'             => now(),                             // Record updated at
        ]);

        return redirect()->route('business.plan.view')->with('success', 'Business Plan Added Successfully!');
    }

    public function businessPlanEdit($id)
    {
        $userId     = Session::get('app_user_id');
        $appUser    = DB::table('app_users')->where('id', $userId)->first();
        $categories = DB::table('business_category')->get();
        $plan       = DB::table('business_plans')->where('id', $id)->first();

        if (! $plan) {
            return redirect()->back()->with('error', 'Plan not found!');
        }

        return view('admin.users.businessPlanAdd', [
            'appUser'    => $appUser,
            'categories' => $categories,
            'plan'       => $plan,
            'isEdit'     => true,
        ]);
    }

    public function businessPlanUpdate(Request $request, $id)
    {
        $plan = DB::table('business_plans')->where('id', $id)->first();
        if (! $plan) {
            return redirect()->back()->with('error', 'Plan not found!');
        }

        $category = DB::table('business_category')->where('id', $request->business_category_id)->first();

        DB::table('business_plans')->where('id', $id)->update([
            'business_category_id'   => $category->id,                   // ID from business_category table
            'business_category_name' => $category->category_name,        // Name from business_category table
            'loan_amount'            => $request->loan_amount,           // Loan amount requested
            'extra_amount'           => $request->extra_amount,          // Any extra amount
            'number_of_days'         => $request->number_of_days,        // Number of days for loan
            'membership_per'         => $request->membership_per,        // Membership %
            'membership_charge'      => $request->membership_charge,     // Membership charge
            'emi_amount'             => $request->emi_amount,            // EMI amount
            'processing_charge'      => $request->processing_charge,     // Processing fee
            'loan_insurance_charge'  => $request->loan_insurance_charge, // Loan insurance charge
            'other_charges'          => $request->other_charges,         // Any other charges
            'interest_amount'        => $request->interest_amount,       // Interest amount
            'interest_rate'          => $request->interest_rate,         // Interest rate
            'final_amount'           => $request->final_amount,          // Final amount to be paid
            'status'                 => 1,                               // 0=Inactive, 1=Active
            'updated_at'             => now(),                           // Record updated at
        ]);

        return redirect()->route('business.plan.view')->with('success', 'Business Plan Updated Successfully!');
    }

    public function businessPlanView()
    {
        $userId   = session('app_user_id');
        $userName = DB::table('app_users')->where('id', $userId)->value('app_u_name');
        $plans    = DB::table('business_plans')->where('user_by', $userId)->orderByDesc('id')->get();
        return view('admin.users.businessPlanView', compact('plans', 'userName'));
    }

    public function businessPlanToggle($id)
    {
        $plan = DB::table('business_plans')->where('id', $id)->first();
        if (! $plan) {
            return back()->with('error', 'Plan not found.');
        }

        $newStatus = $plan->status == 1 ? 0 : 1;
        DB::table('business_plans')->where('id', $id)->update(['status' => $newStatus]);

        return back()->with('success', 'Plan status updated successfully.');
    }

    public function businessPlanDelete($id)
    {
        $plan = DB::table('business_plans')->where('id', $id)->first();
        if (! $plan) {
            return back()->with('error', 'Plan not found.');
        }

        DB::table('business_plans')->where('id', $id)->delete();

        return back()->with('success', 'Business Plan deleted successfully.');
    }

    // === DAILY UPDATE ===
// show blank form for creating

// === DAILY UPDATE ===

    public function dailyUpdateAdd()
    {
        $userId = session('app_user_id');

        // Get only business plans created by this user
        $plans = DB::table('business_plans')
            ->where('user_by', $userId)
            ->select('id', 'business_category_name')
            ->get();

        $isEdit = false;

        return view('admin.users.dailyUpdateAdd', compact('plans', 'isEdit'));
    }

    public function dailyUpdateStore(Request $request)
    {
        $userId  = Session::get('app_user_id');
        $appUser = DB::table('app_users')->where('id', $userId)->first();
        $plan    = DB::table('business_plans')->where('id', $request->business_plan_id)->first();

        DB::table('daily_update')->insert([
            'user_by'                    => $userId,
            'add_user_name'              => $appUser->app_u_name ?? 'Unknown',
            'month_name'                 => $request->month_name,
            'business_plan_id'           => $plan->id,
            'business_plan_name'         => $plan->business_category_name,
            'date_entry'                 => $request->date_entry,
            'today_emi'                  => $request->today_emi,
            'today_close_customers'      => $request->today_close_customers,
            'today_new_customers'        => $request->today_new_customers,
            'total_daily_colletion'      => $request->total_daily_colletion,
            'total_weekly_colletion'     => $request->total_weekly_colletion,
            'total_bi_weekly_colletion'  => $request->total_bi_weekly_colletion,
            'total_monthly_colletion'    => $request->total_monthly_colletion,
            'today_loan_in_ac'           => $request->today_loan_in_ac,
            'today_loan_in_cash'         => $request->today_loan_in_cash,
            'today_total_loan_amount'    => $request->today_total_loan_amount,
            'today_closing_balance_ac'   => $request->today_closing_balance_ac,
            'today_closing_balance_cash' => $request->today_closing_balance_cash,
            'rd_amount'                  => $request->rd_amount,
            'rd_withdrawal'              => $request->rd_withdrawal,
            'rd_interest'                => $request->rd_interest,
            'current_balance'            => $request->current_balance,
            'created_at'                 => now(),
            'updated_at'                 => now(),
        ]);

        return redirect()->route('daily.update.view')->with('success', 'Daily update saved successfully.');
    }

    public function dailyUpdateView()
    {
        $userId   = session('app_user_id');
        $userName = DB::table('app_users')->where('id', $userId)->value('app_u_name');

        $updates = DB::table('daily_update')
            ->where('user_by', $userId)
            ->orderByDesc('id')
            ->get();

        return view('admin.users.dailyUpdateView', [
            'updates'  => $updates,
            'userName' => $userName,
        ]);
    }

    public function dailyUpdateEdit($id)
    {
        $update = DB::table('daily_update')->where('id', $id)->first();
        $plans  = DB::table('business_plans')->select('id', 'business_category_name')->get();
        $isEdit = true;
        return view('admin.users.dailyUpdateAdd', compact('update', 'plans', 'isEdit'));
    }

    public function dailyUpdateUpdate(Request $request, $id)
    {
        $plan = DB::table('business_plans')->where('id', $request->business_plan_id)->first();

        DB::table('daily_update')->where('id', $id)->update([

            'month_name'                 => $request->month_name,                 // Month name of the daily update
            'business_plan_id'           => $plan->id,                            // ID from business_plans table
            'business_plan_name'         => $plan->business_category_name,        // Business plan name from business_plans table
            'date_entry'                 => $request->date_entry,                 // Date of this daily update
            'today_emi'                  => $request->today_emi,                  // EMI collected today
            'today_close_customers'      => $request->today_close_customers,      // Number of customers closed today
            'today_new_customers'        => $request->today_new_customers,        // Number of new customers today
            'total_daily_colletion'      => $request->total_daily_colletion,      // Total daily collection
            'total_weekly_colletion'     => $request->total_weekly_colletion,     // Total weekly collection
            'total_bi_weekly_colletion'  => $request->total_bi_weekly_colletion,  // Total bi-weekly collection
            'total_monthly_colletion'    => $request->total_monthly_colletion,    // Total monthly collection
            'today_loan_in_ac'           => $request->today_loan_in_ac,           // Loan received in account today
            'today_loan_in_cash'         => $request->today_loan_in_cash,         // Loan received in cash today
            'today_total_loan_amount'    => $request->today_total_loan_amount,    // Total loan amount received today
            'today_closing_balance_ac'   => $request->today_closing_balance_ac,   // Closing account balance today
            'today_closing_balance_cash' => $request->today_closing_balance_cash, // Closing cash balance today
            'rd_amount'                  => $request->rd_amount,
            'rd_withdrawal'              => $request->rd_withdrawal,
            'rd_interest'                => $request->rd_interest,
            'current_balance'            => $request->current_balance, // Current total balance
            'updated_at'                 => now(),                     // Record updated at
        ]);

        return redirect()->route('daily.update.view')->with('success', 'Daily update updated successfully.');
    }

    public function dailyUpdateDelete($id)
    {
        DB::table('daily_update')->where('id', $id)->delete();
        return redirect()->route('daily.update.view')->with('success', 'Daily update deleted successfully.');
    }

// === Monthly UPDATE ===

    public function monthlyUpdateAdd()
    {
        $userId = session('app_user_id');

        // Get only business plans created by this user
        $plans = DB::table('business_plans')
            ->where('user_by', $userId)
            ->select('id', 'business_category_name')
            ->get();

        $isEdit = false;

        return view('admin.users.monthlyUpdateAdd', compact('plans', 'isEdit'));
    }

    public function monthlyUpdateStore(Request $request)
    {
        $userId  = Session::get('app_user_id');
        $appUser = DB::table('app_users')->where('id', $userId)->first();
        $plan    = DB::table('business_plans')->where('id', $request->business_plan_id)->first();

        DB::table('monthly_update')->insert([
            'user_by'                 => $userId,
            'add_user_name'           => $appUser->app_u_name ?? 'Unknown',
            'month_name'              => $request->month_name,
            'director_loan'           => $request->director_loan,
            'bank_loan'               => $request->bank_loan,
            'investment_for_invertor' => $request->investment_for_invertor,
            'director_salary'         => $request->director_salary,
            'staff_salary'            => $request->staff_salary,
            'office_rent'             => $request->office_rent,
            'electricity_bill'        => $request->electricity_bill,
            'recharge_bill'           => $request->recharge_bill,
            'furniture_amount'        => $request->furniture_amount,
            'other_expences'          => $request->other_expences,
            'created_at'              => now(),
            'updated_at'              => now(),
        ]);

        return redirect()->route('monthly.update.add')->with('success', 'Daily update saved successfully.');
    }

// === RD Add ===

    public function businessPlanAddRd()
    {
        $plans  = DB::table('business_plans')->select('id', 'business_category_name')->get();
        $isEdit = false;
        return view('admin.users.businessPlanAddRd', compact('plans', 'isEdit'));
    }

    public function businessPlanStoreRd(Request $request)
    {
        $userId  = Session::get('app_user_id');                           // Get currently logged-in user ID
        $appUser = DB::table('app_users')->where('id', $userId)->first(); // Fetch user details

        // Insert new RD business plan record
        DB::table('business_plans_rd')->insert([
            'user_by'       => $userId,                           // FK to app_users.id, from session
            'add_user_name' => $appUser->app_u_name ?? 'Unknown', // User name fetched from app_users table
            'rd_amount'     => $request->rd_amount,               // RD amount deposited
            'rd_interest'   => $request->rd_interest,             // Interest earned on RD
            'status'        => 1,                                 // 0=Inactive, 1=Active
            'created_at'    => now(),                             // Record created at
            'updated_at'    => now(),                             // Record updated at
        ]);

        return redirect()->route('business.plan.addRd')->with('success', 'Business Plan RD Added Successfully!');
    }

// delete **************************************************

    /*
    Your Controller Method (already good)
    // ✅ Only allow specific tables
        $allowedTables = ['members', 'plans', 'companies'];
    ✅ 2. Define Route in web.php

    Route::get('/delete/{table}/{id}', [MemberController::class, 'deleteFromTable'])->name('generic.delete');

    ✅ 3. Use in Blade (Dynamic Delete Button)

    <a href="{{ route('generic.delete', ['table' => 'members', 'id' => $company->id]) }}"
    onclick="return confirm('Are you sure you want to delete {{ $company->name }}?')"
    class="btn btn-danger">
        <i class="fa fa-trash"></i>
    </a>

    */

    public function deleteFromTable(Request $request, $table, $id)
    {
        // ✅ Only allow specific tables
        $allowedTables = ['members', 'package_master', 'app_banners'];

        if (! in_array($table, $allowedTables)) {
            abort(403, 'Unauthorized table access.');
        }

        DB::table($table)->where('id', $id)->delete();

        // return back()->with('success', ucfirst($table) . ' deleted successfully!');
        return back()->with('success', ' Deleted successfully!');
    }

    // delete **************************************************

    // ***********************************************

}
