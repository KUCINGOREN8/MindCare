<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
  public function index()
{
    $user = Auth::user();

    if ($user->isPatient()) {
        $conversations = Conversation::where('patient_id', $user->id)
            ->with(['psychologist', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $viewData = [
            'title' => 'My Messages',
            'conversations' => $conversations,
            'isPatient' => true,
            'isPsychologist' => false
        ];

    } elseif ($user->isPsychologist()) {
        $conversations = Conversation::where('psychologist_id', $user->id)
            ->with(['patient', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $viewData = [
            'title' => 'Patient Messages',
            'conversations' => $conversations,
            'isPatient' => false,
            'isPsychologist' => true
        ];

    } else {
        return redirect()->route('admin.dashboard')->with('info', 'Chat feature is not available for admin role');
    }
        return view('pages.chat.index', array_merge($viewData, ['user' => $user]));
    }

    public function startChat(User $psychologist)
    {
        $patient = Auth::user();

        if (!$patient->isPatient()) {
            abort(403, 'Unauthorized access');
        }

        if (!$psychologist->isPsychologist()) {
            abort(404, 'Psychologist not found');
        }

        $conversation = Conversation::where('patient_id', $patient->id)
            ->where('psychologist_id', $psychologist->id)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'patient_id' => $patient->id,
                'psychologist_id' => $psychologist->id,
                'status' => 'active'
            ]);
        }

       return redirect()->route('chat.show', $conversation);
    }

    public function show(Conversation $conversation)
{
    $user = Auth::user();

    if (!$conversation->isParticipant($user->id)) {
        abort(403, 'Unauthorized access to this conversation');
    }

    $messages = Message::where('conversation_id', $conversation->id)
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')
        ->get();

    Message::where('conversation_id', $conversation->id)
        ->where('receiver_id', $user->id)
        ->where('is_read', false)
        ->update(['is_read' => true, 'read_at' => now()]);

    if ($user->isPatient()) {
        $conversations = Conversation::where('patient_id', $user->id)
            ->with(['psychologist', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $otherUser = $conversation->psychologist;
        $userType = 'patient';

    } elseif ($user->isPsychologist()) {
        $conversations = Conversation::where('psychologist_id', $user->id)
            ->with(['patient', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $otherUser = $conversation->patient;
        $userType = 'psychologist';

    } else {
        $conversations = collect();
        $otherUser = null;
        $userType = 'admin';
    }

    return view('pages.chat.show', [
        'conversation' => $conversation,
        'messages' => $messages,
        'conversations' => $conversations,
        'user' => $user,
        'otherUser' => $otherUser,
        'userType' => $userType
    ]);
}

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $user = Auth::user();

        if (!$conversation->isParticipant($user->id)) {
            abort(403, 'Unauthorized');
        }

        $receiverId = ($conversation->patient_id == $user->id)
            ? $conversation->psychologist_id
            : $conversation->patient_id;

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender')
            ]);
        }

        return redirect()->route('chat.show', $conversation)->with('success', 'Message sent!');
    }

    public function getMessages(Conversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->isParticipant($user->id)) {
            abort(403, 'Unauthorized');
        }

        $messages = Message::where('conversation_id', $conversation->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

     public function psychologistIndex()
    {
        $user = Auth::user();

        if (!$user->isPsychologist()) {
            abort(403, 'Unauthorized access');
        }

        $conversations = Conversation::where('psychologist_id', $user->id)
            ->with(['patient', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('pages.chat.psychologist-index', [
            'conversations' => $conversations,
            'user' => $user
        ]);
    }

    public function psychologistShow(Conversation $conversation)
    {
        return $this->show($conversation);
    }
}
