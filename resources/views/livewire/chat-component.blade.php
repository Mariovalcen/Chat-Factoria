<div x-data="data()" class="bg-gray-50 rounded-lg shadow border border-gray200 overflow-hidden">

    <div class="grid grid-cols-3 divide-x divide-orange-200">

        <div class="col-span-1">

            <div class="bg-gray-100 h-16 flex items-center px-4">

                <img class="w-10 h-10 object-cover object-center" src="{{ auth()->user()->profile_photo_url }}"
                    alt="{{ auth()->user()->name }}">

            </div>

            <div class="bg-white h-14 flex items-center px-4">

                <x-input type="text" wire:model="search" class="w-full"
                    placeholder="Busque un chat o inicie uno nuevo" />

            </div>

            <div class="h-[calc(100vh-10.5rem)] overflow-auto border-t border-gray-200">
                @if ($this->chats->count() == 0 || $search)

                    <div class="px-4 py-3">
                        <h2 class="text-orange-700 text-lg mb-4">Contáctos</h2>

                        <ul class="space-y-4">
                            @forelse ($this->contacts as $contact)
                                <li class="cursor-pointer" wire:click="open_chat_contact({{ $contact }})">
                                    <div class="flex">

                                        <figure class="flex-shrink-0">
                                            <img class="h-12 w-12 object-cover object-center rounded-full"
                                                src="{{ $contact->user->profile_photo_url }}"
                                                alt="{{ $contact->name }}">
                                        </figure>


                                        <div class="flex-1 ml-5 border-b border-gray-200">
                                            <p class="text-gray-800">
                                                {{ $contact->name }}
                                            </p>
                                            <p class="text-gray-600 text-xs">
                                                {{ $contact->user->email }}
                                            </p>
                                        </div>

                                    </div>
                                </li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                @else

                    @foreach ($this->chats as $chatItem)
                        <div wire:key="chats-{{ $chatItem->id }}" wire:click="open_chat({{ $chatItem }})"
                            class="flex items-center justify-between {{ $chat && $chat->id == $chatItem->id ? 'bg-gray-200' : 'bg-white' }} hover:bg-gray-100 cursor-pointer px-3">

                            <figure>
                                <img src="{{ $chatItem->image }}"
                                    class="h-12 w-12 object-cover object-center rounded-full"
                                    alt="{{ $chatItem->name }}">
                            </figure>

                            <div class="w-[calc(100%-4rem)] py-4 border-b border-gray-200">

                                <div class="flex justify-between items-center">
                                     <p>
                                    {{ $chatItem->name }}
                                </p>

                                <p class="text-gray-600 text-xs">
                                    {{ $chatItem->last_message_at->format('h:i A') }}
                                </p>
                                </div>
                                
                                <p class="text-sm text-gray-700 mt-1 truncate">
                                    {{ $chatItem->messages->last()->body }}
                                </p>
                               
                            </div>

                        </div>
                    @endforeach

                @endif
            </div>

        </div>



        <div class="col-span-2">

            @if ($contactChat || $chat)

                <div class="bg-gray-100 h-16 flex items-center px-3">

                    <figure>

                        @if ($chat)
                            <img class="w-10 h-10 rounded-full object-cover object-center" src="{{ $chat->image }}"
                                alt="{{ $chat->name }}">
                        @else
                            <img class="w-10 h-10 rounded-full object-cover object-center"
                                src="{{ $contactChat->user->profile_photo_url }}" alt="{{ $contactChat->name }}">
                        @endif

                    </figure>

                    <div class="ml-4">
                        <p class="text-gray-800">

                            @if ($chat)
                                {{ $chat->name }}
                            @else
                                {{ $contactChat->name }}
                            @endif

                        </p>

                        <p class="text-gray-600 text-xs" x-show="chat_id == typingChatId">
                            Escribiendo ...
                        </p>

                        @if ($this->active)
                            <p class="text-green-500 text-xs" x-show="chat_id != typingChatId" wire:key="online">
                                Online
                            </p>
                        @else
                            <p class="text-red-600 text-xs" x-show="chat_id != typingChatId" wire:key="offline">
                                Offline
                            </p>
                        @endif
                    </div>

                </div>

                <div class="h-[calc(100vh-11rem)] px-3 py-2 overflow-auto">
                    {{-- El contenido de nuestro chat --}}
                    @foreach ($this->messages as $message)
                        <div class="flex {{ $message->user_id == auth()->id() ? 'justify-end' : '' }} mb-2">

                            <div class="rounded px-3 py-2 {{ $message->user_id == auth()->id() ? 'bg-orange-100' : 'bg-gray-200' }} ">
                                <p class="text-sm">
                                    {{ $message->body }}
                                </p>

                                <p class="{{ $message->user_id == auth()->id() ? 'text-right' : '' }} text-xs text-gray-600 mt-1">
                                    {{ $message->created_at->format('d-m-y h:i A') }}
                                </p>
                            </div>

                        </div>
                    @endforeach

                    <span id="final"></span>

                </div>


                <form class="bg-gray-100 h-16 flex items-center px-4" wire:submit.prevent="sendMessage()">
                    <x-input wire:model="bodyMessage" type="text" class="flex-1"
                        placeholder="Escriba un mensaje aquí" />

                    <button class="flex-shrink-0 ml-4 text-2xl text-gray-700">
                        Enviar
                    </button>
                </form>
            @else
                <div class="w-full h-full flex justify-center items-center">
                    <div>
                        <div>
                            <img src="data:image/png;base64,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"
                                alt="">
                        </div>
                        <h1 class="text-center text-gray-500 text-2xl mt-4">Chat FactoriAcademy</h1>
                    </div>
                </div>

            @endif

        </div>


    </div>
    @push('js')
    <script>
        function data() {
                    return {
                        chat_id: @entangle('chat_id'),
                        typingChatId: null,
                        init() {

                            Echo.private('App.Models.User.' + {{ auth()->id() }})
                                .notification((notification) => {
                                    if (notification.type == 'App\\Notifications\\UserTyping') {
                                        this.typingChatId = notification.chat_id;
                                        setTimeout(() => {
                                            this.typingChatId = null;
                                        }, 3000);
                                    }

                                });
                        }
                    }
                }
        
        Livewire.on('scrollIntoView', function() {
            document.getElementById('final').scrollIntoView(true);
        });
    </script>
@endpush
</div>
