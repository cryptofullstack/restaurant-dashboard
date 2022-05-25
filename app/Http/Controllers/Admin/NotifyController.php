<?php

namespace App\Http\Controllers\Admin;

use FCM;
use App\User;
use App\Badge;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\PayloadNotificationBuilder;

class NotifyController extends Controller
{
    public function viewPush()
    {
        return view('admin.push');
    }

    public function getAllData()
    {
        $notifications = Notification::join('users','users.id', '=', 'notifications.user_id')->select('notifications.*', 'users.name')->orderBy('notifications.created_at', 'DESC')->get();

        return response()->json($notifications, 200);
    }

    public function sendNotify(Request $request)
    {
        $selected_users = $request->push_users;

        foreach ($selected_users as $user_id) {
            $user = User::find($user_id);
            if ($user) {
                $budge_number = 1;
                $current_badge_count = Badge::where('user_id', $user->id)->count();

                if ($current_badge_count > 0) {
                    $current_badge = Badge::where('user_id', $user->id)->first();
                    $current_badge->badge_num = $current_badge->badge_num+1;
                    $current_badge->save();

                    $budge_number = $current_badge->badge_num;
                } else {
                    Badge::create([
                        'user_id' => $user->id,
                        'badge_num' => 1
                    ]);
                }

                if ($user->device_fcm) {
                    Notification::create([
                        'user_id' => $user->id,
                        'push_title' => $request->push_title,
                        'push_body' => $request->push_body,
                    ]);

                    $notificationBuilder = new PayloadNotificationBuilder($request->push_title);
                    $notificationBuilder->setBody($request->push_body)
                    				    ->setSound('default')
                                        ->setBadge($budge_number);
                    $notification = $notificationBuilder->build();

                    $downstreamResponse = FCM::sendTo($user->device_fcm, null, $notification, null);

                    $downstreamResponse->numberSuccess();
                    $downstreamResponse->numberFailure();
                    $downstreamResponse->numberModification();
                    $downstreamResponse->tokensToDelete();
                    $downstreamResponse->tokensToModify();
                    $downstreamResponse->tokensToRetry();
                }
            }
        }

        return response()->json(['result' => 'success']);
    }
}
