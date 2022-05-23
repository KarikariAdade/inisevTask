<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HelperFunctions;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WebsiteController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new HelperFunctions();
    }


    public function list()
    {
        $websites = Website::query()->orderBy('id', 'DESC')->paginate(20);

        return $this->helper->successResponse($websites);

    }


    public function store(Request $request)
    {
        $data = $request->only(['name', 'url', 'code', 'description']);

        $validate = Validator::make($data, $this->validateWebsiteFields());

        if ($validate->fails()){
            return $this->helper->failResponse($validate->errors()->first());
        }

        DB::beginTransaction();

        try{

            $website = Website::query()->create($this->dumpWebsiteData($data));

            DB::commit();

            return $this->helper->successResponse("$website->name created successfully");

        }catch (\Exception $exception){

            DB::rollback();

            Log::error($exception->getMessage().' on Line: '.$exception->getLine());

            return $this->helper->failResponse("Website could not be created. Kindly try again");

        }



    }


    public function subscribe(Request $request)
    {
        $data = $request->only(['website_code', 'user_code']);

        $validate = Validator::make($data, [
            'user_code' => 'required',
            'website_code' => 'required'
        ]);

        if ($validate->fails()){
            return $this->helper->failResponse($validate->errors()->first());
        }

        $check_validity = $this->validateSubscriptionData($data['user_code'], $data['website_code']);

        if ($check_validity['status'] == true){

            DB::beginTransaction();

            try{

                Subscription::query()->create([
                    'user_id' => $check_validity['user']->id,
                    'website_id' => $check_validity['website']->id
                ]);

                DB::commit();

                return $this->helper->successResponse($check_validity['user']->name .' subscribed to '. $check_validity['website']->name.' successfully');

            }catch (\Exception $exception){

                DB::rollback();

                Log::error($exception->getMessage().' on Line: '.$exception->getLine());

                return $this->helper->failResponse("Subscription unsuccessful");

            }

        }else{
            return $this->helper->failResponse('Invalid User or Website Code supplied');
        }


    }

    public function validateSubscriptionData($user, $website)
    {
        $user = User::query()->where('code', $user)->first();

        $website = Website::query()->where('code', $website)->first();

        if (!empty($user) && !empty($website)){

            return [
                'status' => true,
                'user' => $user,
                'website' => $website
            ];
        }

        return [
            'status' => false
        ];
    }


    public function viewSubscriptions(Request $request)
    {
        return $request->all();
    }

    public function validateWebsiteFields()
    {
        return [
            'name' => 'required|string',
            'url' => 'nullable|url',
            'code' => 'required|unique:websites,code',
            'description' => 'nullable|min: 20|max: 200'
        ];
    }

    public function dumpWebsiteData($data)
    {
        return [
            'name' => $data['name'],
            'code' => $data['code'],
            'url' => $data['url'],
            'description' => $data['description']
        ];
    }

}
