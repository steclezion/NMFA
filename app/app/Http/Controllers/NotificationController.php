<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\uploaded_documents;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function MongoDB\BSON\toJSON;

class NotificationController extends Controller
{
    public function index_notification_reminders()
    {
        $notifications =Notification::join('Users','notifications.from_user','users.id')
            ->select(
                'users.first_name',
                'notifications.*'
            )
            ->orderByDesc('notifications.id')
            ->get();

        $breadcrumb_title='Notifications & Reminders';
        return view('notification.index',['notifications'=>$notifications,'breadcrumb_title'=>$breadcrumb_title]);
    }

    public function index_notifications()
    {
        $notifications =Notification::join('Users','notifications.from_user','users.id')
            ->select(
                'users.first_name',
                'notifications.*'
            )
            ->orderByDesc('notifications.id')
            ->get();

        $breadcrumb_title='Notifications';
        return view('notification.index',['notifications'=>$notifications,'breadcrumb_title'=>$breadcrumb_title]);
    }

    public function index_reminders()
    {
        $notifications =Notification::join('Users','notifications.from_user','users.id')
            ->select(
                'users.first_name',
                'notifications.*'
            )
            ->orderByDesc('notifications.id')
            ->get();

        $breadcrumb_title='Reminders';
        return view('notification.index',['notifications'=>$notifications,'breadcrumb_title'=>$breadcrumb_title]);
    }

    public function index_messages()
    {
        $notifications =Notification::join('Users','notifications.from_user','users.id')
            ->select(
                'users.first_name',
                'notifications.*'
            )
            ->orderByDesc('notifications.id')
            ->get();

        $breadcrumb_title='Messages';
        return view('notification.index',['notifications'=>$notifications,'breadcrumb_title'=>$breadcrumb_title]);
    }

    public function show_notification($id)
    {
        //$notification = Notification::find($id);
        $notification =Notification::join('users','notifications.from_user','users.id')
            ->select(
                'users.first_name',
                'users.middle_name',
                'notifications.*'
            )
            ->find($id);


        $breadcrumb_title='Notification Detail';
        return view('notification.detail',['notification'=>$notification, 'breadcrumb_title'=>$breadcrumb_title]);
    }

    public function filter_notification($id)
    {
        $notifications =Notification::join('users','notifications.from_user','users.id')
            ->select(
                'users.first_name',
                'notifications.*'
            )
            ->where('from_user',$id)
            ->Orwhere('to_user',$id)
            ->orderByDesc('notifications.id')
            ->get();

        //dd($notifications);

        $breadcrumb_title='Messages';
        //return view('notification.index',['notifications'=>$notifications,'breadcrumb_title'=>$breadcrumb_title]);
        return view('notification.filtered_messages',['notifications'=>$notifications,'breadcrumb_title'=>$breadcrumb_title]);
    }
    public function index()
    {
        // $notifications =Notification::join('Users','notifications.from_user','users.id')
        //     ->select(
        //         'users.first_name',
        //         'notifications.*'
        //     )
        //     ->orderByDesc('notifications.id')
        //     ->get();
            $notifications=auth()->user()->notifications;
            //we must add the user session sothat he can only see his  own
            //also we need to check weather he is perc or not if he is perc he must see perc notfications


//        dd($notifications);
        $breadcrumb_title='Notifications & Reminders';
        return view('notification.index',['notifications'=>$notifications,'breadcrumb_title'=>$breadcrumb_title]);

    }

public function show($id){
   Notification::where('id',$id)->update(['read_at'=>now()]);
    $notification=Notification::find($id);
        $user=user::find($notification->notifiable_id);
        $data=json_decode($notification['data']);
        if($data->related_document!=null) {
            $document_id = $data->related_document;
            $document=uploaded_documents::find($document_id);
        }
        else {
            $document = null;
        }

        return view('notification.detail',['document'=>$document,'user'=>$user,'notification'=>$data,'data'=>$notification]);
        //here data must separated then get the uploaded document and after that show the details



}
function retrieve_notifications(){
        try{
            $notifications=auth()->user()->unreadNotifications;
            $notification_count=count($notifications);
            $counter=1;

            $notifications_ui="";
            $notification_show="notification_show";
            foreach ($notifications as $notification)
            {

                if ($notification->data["type"] == 'Notification') {
                    $notification_icon = 'fa-envelope';
                }elseif($notification->data["type"] == 'Reminder'){
                    $notification_icon = ' fa-stopwatch';
                }

                $notifications_ui .= '<a  href="/Notifications/show/' . $notification->id . '" class="dropdown-item">
                    <i class="fas '. $notification_icon.'"></i> ' .
                    $notification->data["subject"] . '<span class="float-right text-muted text-sm"></span></a>';
            }
            return response()->json(['notifications' => $notifications_ui, 'notification_count' =>$notification_count]);
        }
        catch (\Exception $e) {
        return response()->json(['data' => $e, 'item' => 'error' . $e]);
    }
        return response()->json(['data' => 'generic', 'item' => 'item_success']);

}

}
