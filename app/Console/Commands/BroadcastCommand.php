<?php

namespace App\Console\Commands;

use App\Jobs\BroadcastPost;
use App\Mail\BroadcastMail;
use App\Models\Posts;
use App\Models\Subscription;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        $posts = Posts::query()->where('is_dispatched', 0)->get();

        $posts->map(function ($query){

            $website = Website::query()->where('id', $query->id)->first();

            if (!empty($website)){


                $subscriptions = Subscription::query()->where('website_id', $website->id)->get();

                if (!empty($subscriptions)){


                    $subscriptions->map(function ($subscribed_item) use($query){

                        $data = [
                            'name' => $subscribed_item->user->name,
                            'email' => $subscribed_item->user->email,
                            'subject' => 'Email Notification: '.$query->title,
                            'body' => $query->description,
                        ];

                        Mail::send(new BroadcastMail($data));
                    });

                }


            }


            DB::table('posts')->where('id', $query->id)->update(['is_dispatched' => true]);

        });
    }
}
