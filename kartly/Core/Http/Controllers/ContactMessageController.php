<?php

namespace Core\Http\Controllers;

use Core\Models\ContactMessage;
use App\Http\Controllers\Controller;
use Core\Http\Requests\ContactMessageRequest;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{


    public function storeContactMessage(ContactMessageRequest $request)
    {
        try {
            $message = new ContactMessage();
            $message->email = $request->email;
            $message->name = $request->name;
            $message->message = $request->message;
            $message->subject = $request->subject;
            $message->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => translate('Unable to send message', session()->get('api_locale'))
            ]);
        }
    }
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('core::base.contact.messages', ['messages' => $messages]);
    }

    public function delete(Request $request)
    {
        try {
            ContactMessage::find($request->id)->delete();
            toastNotification('success', translate('Message deleted successfully'), 'Success');
            return redirect()->route('core.contact.messages');
        } catch (\Exception $e) {
            toastNotification('error', translate('Message delete failed'), 'Failed');
            return redirect()->back();
        }
    }
}