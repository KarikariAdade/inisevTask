<?php

namespace App\Http\Controllers\Api;

use App\Helpers\HelperFunctions;
use App\Http\Controllers\Controller;
use App\Models\Posts;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new HelperFunctions();
    }

    public function listPosts()
    {
        $posts = Posts::query()->orderBy('id', 'DESC')->paginate(20);

        return $this->helper->successResponse($posts);
    }


    public function store(Request $request)
    {

        $data = $request->only(['title', 'description', 'website_code']);

        // Validate form fields
        $validate = Validator::make($data, $this->validatePostFields());

        if ($validate->fails()){
            return $this->helper->failResponse($validate->errors()->first());
        }

        // Check if selected website exists
        $validate_website = Website::query()->where('code', $data['website_code'])->first();

        if (!empty($validate_website)){

            $data['website_code'] = $validate_website->id;

            DB::beginTransaction();

            try {

                Posts::query()->create($this->dumpPostData($data));

                DB::commit();

                return $this->helper->successResponse("Post created successfully");

            } catch (\Exception $e){

                DB::rollback();

                Log::error($e->getMessage().' Line: '.$e->getLine());

                return $this->helper->failResponse("Post could not be created. Kindly try again");

            }
        }

        return $this->helper->failResponse('The selected website does not exsit');


    }

    // Validation rules
    public function validatePostFields()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'website_code' => 'required'
        ];
    }


    public function runDispatch()
    {
        Posts::where('is_dispatched', 0)->chunk(1, function ($all_posts) {

            dd($all_posts);
//            $query->map(function ($post) {
//
//
//
//                $website = Website::query()->where('id', $post->website_id)->first();
//
//
//
//
//
//            });
        });
    }


    // Prepare db dump data
    public function dumpPostData($data)
    {
        return [
            'title' => $data['title'],
            'description' => $data['description'],
            'website_id' => $data['website_code']
        ];
    }
}
