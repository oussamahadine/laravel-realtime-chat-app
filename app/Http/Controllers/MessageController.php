<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\ChatMessage;

class MessageController extends Controller
{

    /**
     * 
     */

    public function users()
    {
        $users = User::select(['id', 'name'])->where('id', '!=', Auth::user()->id)->get()->unique('id');

        return view('chat.users', ['users' => $users]);
    }

    /**
     * 
     */

    public function index()
    {
        $user = Auth::user()->id;
        $user_to = request('user_to');
        $name = request('name');
        $messages = [];

        
        $contacts = Message::where('user_from', '=', $user)
            ->orWhere('user_to', '=', $user)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($item) use ($user) {
                return $item->user_from == $user ? $item->user_to : $item->user_from;
            });

        $contacts = $contacts->map(function ($contact) {
            $user_id = $contact->user_from == Auth::user()->id ? $contact->user_to : $contact->user_from;
            $user = User::findOrFail($user_id);

            return [
                'id' => $user_id,
                'name' => $user->name,
            ];
        });



        //get messges
        if ($user_to) {
            $messages = Message::where(function ($query) use ($user, $user_to) {
                $query->where('user_from', $user)
                    ->where('user_to', $user_to);
            })
                ->orWhere(function ($query) use ($user, $user_to) {
                    $query->where('user_from', $user_to)
                        ->where('user_to', $user);
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }
        

        return view('chat.chat', ['contacts' => $contacts, 'messages' => $messages, 'user' => $user, 'user_to'=> $user_to, 'name'=>$name]);
    }



    /**
     * Validate and store a new message in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required'
        ]);
         

        $message = new Message([
            'user_from' => Auth::user()->id,
            'user_to' =>  $request->user_to,
            'message' => $validated['message']
        ]);

        $message->save();

        // ChatMessage::dispatch($message);
         broadcast(new ChatMessage($message));

        return redirect()->back();
    }
}