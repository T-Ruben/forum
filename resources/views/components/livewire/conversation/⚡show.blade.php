<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use App\Models\Conversation;
use App\Models\Message;
use App\Actions\Conversation\CreateMessageAction;
use App\Actions\Conversation\DeleteMessageAction;
use App\Actions\Conversation\UpdateMessageAction;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use App\Events\MessageSent;
use Livewire\Attributes\On;

new class extends Component
{
    use WithPagination;

    public Conversation $conversation;

    public $replyTo = null;
    public $editMessageId = null; // Renamed to avoid confusion with the model

    #[Validate('required|string|min:1|max:1000')]
    public $content = '';

    public function mount(Conversation $conversation)
    {
        $this->conversation = $conversation;
        // Gate::authorize('view', $this->conversation);
    }

    // Computed Property for the Reply Preview
    public function getReplyToMessageProperty()
    {
        return $this->replyTo ? Message::with('user')->find($this->replyTo) : null;
    }

    public function setReply($id)
    {
        $this->cancel(); // Reset state before setting new action
        $this->replyTo = (int) $id;
    }

    public function setEdit($id)
    {
        $this->cancel();
        $message = Message::where('conversation_id', $this->conversation->id)->findOrFail($id);

        Gate::authorize('update', $message);

        $this->editMessageId = $message->id;
        $this->content = $message->content;
    }

    public function cancel()
    {
        $this->reset(['content', 'replyTo', 'editMessageId']);
        $this->resetValidation();
    }

    public function submit()
    {
        if ($this->editMessageId) {
            $this->updateMessage();
        } else {
            $this->createMessage();
        }
    }

    protected function createMessage()
    {
        $this->authorize('create', [Message::class, $this->conversation]);

        $this->content = trim(strip_tags($this->content));

        $key = 'post-limit' . auth()->id();
        if (RateLimiter::tooManyAttempts($key, 1)) {
            $this->addError('content', "Wait " . RateLimiter::availableIn($key) . "s.");
            return;
        }


        $this->validate();

        app(CreateMessageAction::class)->execute(
            auth()->user(),
            [
                'content' => $this->content,
                'conversation_id' => $this->conversation->id,
                'parent_id' => $this->replyTo,
            ]
        );

        RateLimiter::hit($key, 3);
        $this->cancel();
    }

    #[On('echo-private:chat.{conversation.id},MessageSent')]
    public function handleMessageSent($event)
    {

    }

    protected function updateMessage()
    {
        $message = Message::findOrFail($this->editMessageId);
        Gate::authorize('update', $message);

        $this->validate();

        app(UpdateMessageAction::class)->execute(
            $message,
            ['content' => trim(strip_tags($this->content))]
        );

        $this->cancel();
    }

    public function delete(Message $message)
    {
        Gate::authorize('delete', $message);
        app(DeleteMessageAction::class)->execute($message);
    }

    public function render()
    {
        return view('components.livewire.conversation.⚡show', [
            'messages' => $this->conversation->messages()
                ->with(['parent.user', 'user',])
                ->orderBy('created_at', 'asc')
                ->paginate(10)
        ]);
    }
};
?>

<div class="bg-gray-400 ">
    <div wire:key="messages-container">
        @foreach ($messages as $message)
            {{-- User Profile --}}
            <div wire:key="message-{{ $message->id }}"
                @class([
                'my-0.5 p-2 flex flex-col max-sm:flex-col h-fit hover:bg-gray-300 text-black duration-100',
                'border-indigo-700 shadow-lg border-2 bg-gray-300/75' => request('highlight') == $message->id,
                '' => request('highlight') != $message->id])>
                <div class="h-full py-3
                    text-left flex min-w-42 max-w-42
                    max-sm:min-w-full max-sm:flex-row max-sm:pl-2 max-sm:max-h-32 max-sm:py-2 max-sm:items-start">
                    <div class="w-12 h-12 max-sm:min-w-12 shrink-0 max-sm:min-h-12 max-sm:max-h-12 max-sm:max-w-12 overflow-hidden text-black">
                        <a href="{{ $message->user?->user_url }}" class="w-full h-full">
                        <img src="{{ $message->user->profile_image_url }}"
                            class="w-full h-full object-cover rounded-full border"
                            alt="{{ $message->user->display_name ?? 'Deleted Member' }}'s profile image"
                            data-pin-nopin="true">
                        </a>
                    </div>
                    <div class="flex flex-col w-full px-2">
                        <div>
                            <div class="pt-2 font-bold">
                                <a class="hover:underline" href="{{ $message->user?->user_url }}">
                                {{ $message->user->display_name }}
                                </a>
                            </div>
                            <div class="text-sm text">
                                {{ $message->user->role->label() }}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Content --}}
                <div class="py-2 pr-2 pl-5 w-full min-h-full text-md break-words overflow-hidden ">
                    <article class="post-content break-words" id="message-{{ $message->id }}">
                        @if ($message->parent)
                            <blockquote wire:key="reply-to-{{ $message->parent_id }}"
                                class="flow-root border border-gray-600 p-1 rounded bg-white/25 ">
                                <div class="border-b b-2 py-2 leading-0">
                                    <p class="text-sm inline">Replying to: <span class="font-semibold hover:underline duration-200"><a href="{{ route('conversation.show', [$conversation, 'page' => $message->parent->getPageNumber()]) }}#message-{{ $message->parent_id }}">{{ $message->parent?->user->display_name }}</a></span></p>
                                </div>

                                <div class="relative">
                                    <input type="checkbox" id="readmore-{{ $message->id }}" class="peer hidden">

                                    <div class="whitespace-pre-line line-clamp-5 peer-checked:line-clamp-none">
                                        {!! \App\Services\BBCodeParser::parse($message->parent?->content) !!}
                                    </div>

                                    @if (strlen($message->parent?->content) > 300)
                                    <label for="readmore-{{ $message->id }}"
                                        class="select-none cursor-pointer text-blue-500 hover:underline mt-2 block peer-checked:hidden">
                                        Read more...
                                    </label>

                                    <label for="readmore-{{ $message->id }}"
                                        class="select-none cursor-pointer text-blue-500 hover:underline mt-2 hidden peer-checked:block">
                                        Show less
                                    </label>
                                    @endif
                                </div>
                            </blockquote>
                        @endif
                        <div class="whitespace-pre-line my-2">{!! \App\Services\BBCodeParser::parse($message->content) !!}</div>
                    </article>
                    <hr class="border-gray-500 my-2">
                    <div class="flex align-bottom">
                        <div class="w-full">
                        <span class="text-sm text-gray-700/75">
                            <x-user-link :user="$message->user" />
                        </span>
                        <span class="text-sm text-gray-700/75">
                            <x-time-display :time="$message->updated_at" :createdAt="$message->created_at" :updatedAt="$message->updated_at"/>
                        </span>
                        </div>

                        <div class="flex gap-5">
                            @can('delete', $message)
                                <button wire:click="delete({{ $message->id }})"
                                    onclick="return confirm('Confirm to delete.')"
                                    class="cursor-pointer text-blue-900 hover:text-blue-900/75 hover:underline duration-200 font-semibold">
                                    Delete
                                </button>
                            @endcan

                            @can('update', $message)
                                <button wire:click="setEdit({{ $message->id }});
                                        $dispatch('scroll-to-form');"
                                    class="cursor-pointer text-blue-900 hover:text-blue-900/75 hover:underline duration-200 font-semibold">
                                    Edit
                                </button>
                            @endcan

                            <button wire:click="setReply({{ $message->id }});
                                    $dispatch('scroll-to-form');"
                                    class="cursor-pointer text-blue-900 hover:text-blue-900/75 hover:underline duration-200 font-semibold">
                                Reply
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

{{-- Message form/area --}}

    <div class="flex border border-black min-h-[200px] h-auto w-full max-w-full bg-gray-200/85">

        <div class="w-32 h-32 overflow-hidden border shadow-xs shadow-black m-2 text-black max-sm:hidden">
            @auth
                <a href="{{ route('users.show', auth()->user()->id) }}" class="w-full h-full">
                    <img src="{{ asset(auth()->user()->profile_image_url) }}" class="w-full h-full object-cover"
                        alt="{{ auth()->user()->display_name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
                </a>
            @endauth

            @guest
                <a href="#">
                    <img src="{{ asset('images/default-avatar.png') }}" class="w-full h-full object-cover"
                        alt="Guest's profile image" data-pin-nopin="true">
                </a>
            @endguest
        </div>

        <div class="flex-2 flex-col h-2/3 m-2 min-w-0">
            <div class="">
                @if ($this->replyToMessage)
                    <div wire:key="reply-preview-{{ $this->replyToMessage->id }}"
                        class="mb-4 p-3 border rounded text-sm border-gray-600 text-black">
                        <p class="flex justify-between border-b">
                            <span>Replying to <a href="#message-{{ $this->replyToMessage->id }}"
                                class="hover:underline font-semibold duration-200">{{ $this->replyToMessage->user->display_name }}</a></span>
                            <a href="{{ route('conversation.show', [$conversation, 'page' => request('page')]) }}"
                                class="formReload hover:text-red-500/75 duration-200">@include('icons.cancel')</a>
                        </p>

                        <div class="relative w-full">
                            <input type="checkbox" id="message-{{ $this->replyToMessage->id }}+{{ $this->replyToMessage->user_id }}" class="peer hidden">

                            <div class=" whitespace-pre-line line-clamp-5 peer-checked:line-clamp-none break-words overflow-hidden">
                                <span class="">{{ $this->replyToMessage->content }}</span>
                            </div>

                            @if (strlen($this->replyToMessage->content) > 300)
                            <label for="message-{{ $this->replyToMessage->id }}+{{ $this->replyToMessage->user_id }}"
                                class="select-none cursor-pointer text-blue-500 hover:underline mt-2 block peer-checked:hidden">
                                Read more...
                            </label>

                            <label for="message-{{ $this->replyToMessage->id }}+{{ $this->replyToMessage->user_id }}"
                                class="select-none cursor-pointer text-blue-500 hover:underline mt-2 hidden peer-checked:block">
                                Show less
                            </label>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <form wire:submit.prevent="submit" class="w-full formReload" id="postForm">

                <textarea
                    id="content"
                    x-data
                    wire:model.live="content"
                    @keydown.enter.exact.prevent="$wire.submit()"
                    rows="6"
                    maxlength="1000"
                    class="w-full p-2 bg-gray-200 text-black resize-none border border-gray-600 outline-none"
                    placeholder="Write your post..."
                ></textarea>

                @error('content')
                    <p class="text-red-500">{{ $message }}</p>
                @enderror

                <div class="flex justify-between gap-5">
                    @if ($this->editMessageId || $this->replyTo)
                    <button type="button"
                        wire:click="cancel()"
                        class="text-white  bg-red-700 hover:bg-red-900/80 block border rounded-md p-1 select-none cursor-pointer">
                        Cancel Edit
                    </button>
                    @endif
                    <button type="submit"
                        class="text-white bg-blue-950 hover:bg-blue-900/80 block border rounded-md p-1 select-none cursor-pointer">
                        Post Reply
                    </button>
                </div>
            </form>

            <div class="mt-3" wire:key="messages-pagination">
                {{ $messages->links() }}
            </div>
        </div>



</div>
