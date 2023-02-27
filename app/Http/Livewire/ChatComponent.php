<?php

namespace App\Http\Livewire;

use App\Models\Chat;
use App\Models\Contact;
use Livewire\Component;

use Illuminate\Support\Facades\Notification;

class ChatComponent extends Component
{
    public $search;

    public $contactChat, $chat, $chat_id;

    public $bodyMessage;

     //Propiedad computadas
     public function getContactsProperty(){
        return Contact::where('user_id', auth()->id())
                ->when($this->search, function($query){

                    $query->where(function($query){
                        $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', function($query){
                                $query->where('email', 'like', '%'.$this->search.'%');
                            });
                    });

                })
                ->get() ?? [];
    }
    
    public function getMessagesProperty(){
        return $this->chat ? $this->chat->messages()->get() : [];
        //$this->chat->messages()->get()
        //Message::where('chat_id', $this->chat->id)->get()
    }

    public function getChatsProperty(){
        return auth()->user()->chats()->get()->sortByDesc('last_message_at'); 
    }

    public function getUsersNotificationsProperty(){
        return  $this->chat ? $this->chat->users->where('id', '!=', auth()->id()) : collect();
    }


     //Métodos
     public function open_chat_contact(Contact $contact){

        $chat = auth()->user()->chats()
                    ->whereHas('users', function($query) use ($contact){
                        $query->where('user_id', $contact->contact_id);
                    })
                    ->has('users', 2)
                    ->first();

        if($chat){
            $this->chat = $chat;
            $this->reset('contactChat', 'bodyMessage','search');
           
        }else{
            $this->contactChat = $contact;
            $this->reset('chat', 'bodyMessage','search');
            
        }

    }

    public function open_chat(Chat $chat){
        $this->chat = $chat;
        $this->reset('contactChat', 'bodyMessage');
    }

    public function sendMessage(){
        $this->validate([
            'bodyMessage' => 'required'
        ]);

        if(!$this->chat){
            $this->chat = Chat::create();
            $this->chat->users()->attach([auth()->user()->id, $this->contactChat->contact_id]);
        }

        $this->chat->messages()->create([
            'body' => $this->bodyMessage,
            'user_id' => auth()->user()->id
        ]);

        Notification::send($this->users_notifications, new \App\Notifications\NewMessage());

        $this->reset('bodyMessage', 'contactChat');
    }

    public function render()
    {
        return view('livewire.chat-component')->layout('layouts.chat');
    }
}
