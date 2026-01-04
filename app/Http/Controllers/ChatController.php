<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->otp_verified) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Please verify your email first.');
        }

        if ($user->isPatient() && $user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is not active.');
        }

        if ($user->isPsychologist() && $user->status !== 'active') {
            return redirect()->route('psychologist.dashboard')
                ->with('error', 'Your account is pending admin approval. Chat feature is not available yet.');
        }

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
        }
        elseif ($user->isPsychologist()) {
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
        }
        else {
            return redirect()->route('admin.dashboard')->with('info', 'Chat feature is not available for admin role');
        }

        return view('chat.index', array_merge($viewData, ['user' => $user]));
    }

    private function isWithinSessionTime($conversation)
    {
        if (!$conversation->appointment_id) {
            return false;
        }

        $appointment = Appointment::find($conversation->appointment_id);

        if (!$appointment) {
            return false;
        }

        if ($appointment->status === 'completed') {
            return false;
        }

        if ($appointment->status !== 'confirmed') {
            return false;
        }

        $sessionStart = $appointment->start_date_time;
        $sessionEnd = $appointment->end_date_time;
        $now = now()->timezone(config('app.timezone'));
        $availableFrom = $sessionStart->copy()->subMinutes(30);

        return $now >= $availableFrom && $now <= $sessionEnd;
    }

    public function startChat(User $otherUser)
    {
        $currentUser = Auth::user();

        if (!$currentUser->otp_verified) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Please verify your email first.');
        }

        if ($otherUser->role === 'psychologist' && (!$otherUser->otp_verified || $otherUser->status !== 'active')) {
            abort(404, 'Psychologist not found or not active');
        }

        if ($currentUser->role === 'patient' && $otherUser->role !== 'psychologist') {
            abort(400, 'Patient can only chat with psychologist.');
        }

        if ($currentUser->role === 'psychologist' && $otherUser->role !== 'patient') {
            abort(400, 'Psychologist can only chat with patient.');
        }

        $conversation = Conversation::where(function($query) use ($currentUser, $otherUser) {
                $query->where('patient_id', $currentUser->id)
                    ->where('psychologist_id', $otherUser->id);
            })
            ->orWhere(function($query) use ($currentUser, $otherUser) {
                $query->where('patient_id', $otherUser->id)
                    ->where('psychologist_id', $currentUser->id);
            })
            ->first();

        if (!$conversation) {
            $patient = $currentUser->role === 'patient' ? $currentUser : $otherUser;
            $psychologist = $currentUser->role === 'psychologist' ? $currentUser : $otherUser;

            $conversation = Conversation::create([
                'patient_id' => $patient->id,
                'psychologist_id' => $psychologist->id,
                'status' => 'active',
                'appointment_id' => request()->route('appointment')->id ?? null,
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        if (!$user->otp_verified) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Please verify your email first.');
        }

        if ($user->isPatient() && $user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account is not active.');
        }

        if ($user->isPsychologist() && $user->status !== 'active') {
            return redirect()->route('psychologist.dashboard')
                ->with('error', 'Your account is pending admin approval. Chat feature is not available yet.');
        }

        if (!$conversation->isParticipant($user->id)) {
            abort(403, 'Unauthorized access to this conversation');
        }

        if (!$this->isWithinSessionTime($conversation)) {
            return redirect()
                ->route('chat.show')
                ->with('error', 'Chat session has ended.');
        }

        $messages = Message::where('conversation_id', $conversation->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'attachment_path' => $message->attachment_path,
                    'attachment_name' => $message->attachment_name,
                    'attachment_url' => $message->attachment_path ? asset('storage/' . $message->attachment_path) : null,
                    'sender' => $message->sender,
                    'sender_id' => $message->sender_id,
                    'created_at' => $message->created_at,
                    'is_read' => $message->is_read,
                ];
            });

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
        }
        elseif ($user->isPsychologist()) {
            $conversations = Conversation::where('psychologist_id', $user->id)
                ->with(['patient', 'latestMessage'])
                ->orderBy('last_message_at', 'desc')
                ->get();

            $otherUser = $conversation->patient;
            $userType = 'psychologist';
        }
        else {
            $conversations = collect();
            $otherUser = null;
            $userType = 'admin';
        }

        return view('chat.show', ['conversation' => $conversation, 'messages' => $messages, 'conversations' => $conversations, 'user' => $user, 'otherUser' => $otherUser, 'userType' => $userType]);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required_without:attachment|string|max:1000',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,webp,bmp,svg,pdf,doc,docx,txt,rtf',
        ]);

        $user = Auth::user();

        if (!$conversation->isParticipant($user->id)) {
            abort(403, 'Unauthorized');
        }

        if (!$this->isWithinSessionTime($conversation)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chat is only available during the scheduled session time.',
                    'session_expired' => true
                ], 403);
            }
            return back()->with('error', 'Chat is only available during the scheduled session time.');
        }

        $receiverId = ($conversation->patient_id == $user->id) ? $conversation->psychologist_id : $conversation->patient_id;

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $attachmentName);
            $fileName = time() . '_' . Str::random(10) . '_' . $safeName;
            $attachmentPath = $file->storeAs('chat-attachments', $fileName, 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'message' => $request->message ?? ($attachmentPath ? '📁 Attachment' : ''),
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($user->id == $conversation->patient_id) {
            $conversation->increment('unread_psychologist');
        } else {
            $conversation->increment('unread_patient');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'attachment_path' => $message->attachment_path,
                    'attachment_name' => $message->attachment_name,
                    'attachment_url' => $message->attachment_path ? asset('storage/' . $message->attachment_path) : null,
                    'sender' => [
                        'id' => $user->id,
                        'full_name' => $user->full_name,
                        'photo_url' => $user->photo_url,
                        'gender' => $user->gender,
                    ],
                    'sender_id' => $user->id,
                    'created_at' => $message->created_at,
                    'is_read' => false,
                ]
            ]);
        }
        return redirect()->route('chat.show', $conversation)->with('success', 'Message sent!');
    }

    public function getMessages(Conversation $conversation)
    {
        $user = Auth::user();

        if (!$conversation->isParticipant($user->id)) {
            abort(403);
        }

        if (!$this->isWithinSessionTime($conversation)) {
            return response()->json([], 403);
        }

        $messages = $conversation->messages()
            ->with(['sender'])
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'attachment_path' => $message->attachment_path,
                    'attachment_name' => $message->attachment_name,
                    'attachment_url' => $message->attachment_path ? asset('storage/' . $message->attachment_path) : null,
                    'sender' => [
                        'id' => $message->sender->id,
                        'full_name' => $message->sender->full_name,
                        'photo_url' => $message->sender->photo_url,
                        'gender' => $message->sender->gender,
                    ],
                    'sender_id' => $message->sender_id,
                    'created_at' => $message->created_at,
                    'is_read' => $message->is_read,
                ];
            });

        if (!$this->isWithinSessionTime($conversation)) {
            return response()->json([], 403);
        }

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

        return view('chat.index', [
            'conversations' => $conversations,
            'user' => $user
        ]);
    }

    public function psychologistShow(Conversation $conversation)
    {
        return $this->show($conversation);
    }

    public function startSession(Appointment $appointment)
    {
        $user = Auth::user();

        if (!$user->otp_verified) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Please verify your email first.');
        }

        if ($appointment->psychologist && $appointment->psychologist->user) {
            $psychologistUser = $appointment->psychologist->user;
            if (!$psychologistUser->otp_verified || $psychologistUser->status !== 'active') {
                abort(404, 'Psychologist not found or not active');
            }
        }


        if ($user->role === 'patient') {
            if ($appointment->user_id !== $user->id) {
                abort(403, 'This is not your appointment.');
            }
            return $this->startChat($appointment->psychologist->user);
        }

        if ($user->role === 'psychologist') {
            $psychologist = $user->psychologist;

            if (!$psychologist || $appointment->psychologist_id !== $psychologist->id) {
                abort(403, 'This is not your client session.');
            }
            return $this->startChat($appointment->user);
        }

        abort(403, 'Unauthorized role.');
    }
}
