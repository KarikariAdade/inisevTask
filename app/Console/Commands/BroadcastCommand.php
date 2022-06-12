<?php

namespace App\Console\Commands;

use App\Jobs\BroadcastPost;
use App\Mail\BroadcastMail;
use App\Models\Posts;
use App\Models\Subscription;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BroadcastCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'broadcast:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcast posts to subscribers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all posts that are not dispatched

        DB::beginTransaction();

        try {

            Posts::query()->where('is_dispatched', 0)->chunk(10, function ($all_posts) {


                $all_posts->each(function ($item) {

                    $website = Website::query()->where('id', $item->website_id)->first();

                    if (!empty($website)) {

                        // Get subscriptions of selected website

                        $subscriptions = Subscription::query()->where('website_id', $website->id)->get();

                        $subscriptions->each(function ($subscribed_item) use ($item) {

                            $data = [
                                'name' => $subscribed_item->user->name,
                                'email' => $subscribed_item->user->email,
                                'subject' => 'Email Notification: '.$item->title,
                                'title' => $item->title,
                                'body' => $item->description,
                            ];

                            $count = 0;

                            BroadcastPost::dispatch($data)->delay(5000);

                            Log::alert('============= DISPATCH POST =========='.++$count);



                        });


                    }


                    Log::alert('============= ITEM ID =========='.$item->id);

                    DB::table('posts')->where('id', $item->id)->update(['is_dispatched' => true]);


                    DB::commit();

                });

            });



        } catch (\Exception $exception){

            DB::rollBack();

            Log::alert('============= DISPATCH ERROR ========== MESSAGE: '.$exception->getMessage(). '======== LINE: ========'. $exception->getLine());

        }

    }
}
