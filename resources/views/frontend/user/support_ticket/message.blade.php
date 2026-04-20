@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Ticket Message') }}
@endsection

@section('content')
@php $authUser = auth()->user(); @endphp
<div class="min-h-screen bg-gray-50 py-12">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    
    {{-- Header Section --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6 border border-gray-200">
      <div class="bg-gradient-to-r from-airbnb-red to-rose-600 h-32"></div>
      <div class="px-8 pb-8">
        <div class="flex flex-col md:flex-row items-center md:items-end -mt-16 gap-6">
          <div class="relative">
            <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg bg-gray-200 overflow-hidden">
                @if($authUser->image)
                    <img src="{{ asset('assets/img/users/' . $authUser->image) }}" class="w-full h-full object-cover">
                @else
                    <i data-lucide="user-circle" class="w-full h-full text-gray-400 p-2"></i>
                @endif
            </div>
          </div>
          <div class="text-center md:text-left flex-1">
            <h1 class="text-3xl font-bold text-gray-900">#{{ $ticket->id }} - {{ $ticket->subject }}</h1>
            <div class="flex items-center justify-center md:justify-start gap-3 mt-1">
                @php
                    $statusColors = [
                        'open' => 'bg-green-100 text-green-600',
                        'pending' => 'bg-amber-100 text-amber-600',
                        'closed' => 'bg-neutral-200 text-neutral-600'
                    ];
                @endphp
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $statusColors[strtolower($ticket->status)] ?? 'bg-neutral-100 text-neutral-500' }}">
                    {{ $ticket->status }}
                </span>
                <span class="text-xs text-gray-400">{{ __('Last interaction') }}: {{ \Carbon\Carbon::parse($ticket->last_message)->diffForHumans() }}</span>
            </div>
          </div>
          <div class="flex gap-3">
            <a href="{{ route('user.support_ticket.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition shadow-sm font-semibold text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                {{ __('All Tickets') }}
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Chat & Reply Container --}}
    <div class="grid grid-cols-1 gap-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col min-h-[600px]">
            {{-- Ticket Original Message --}}
            <div class="p-8 border-b border-gray-100 bg-neutral-50 rounded-t-2xl">
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-airbnb-red text-white flex items-center justify-center shrink-0">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h4 class="font-bold text-gray-900">{{ $authUser->name }}</h4>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ __('Author') }}</span>
                            <span class="text-xs text-gray-400">• {{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="prose prose-sm text-gray-600 max-w-none leading-relaxed">
                            {!! $ticket->description !!}
                        </div>
                        @if($ticket->attachment)
                            <div class="mt-4 pt-4 border-t border-neutral-200">
                                <a href="{{ asset('assets/admin/img/support-ticket/attachment/' . $ticket->attachment) }}" download 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-neutral-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-neutral-50 transition shadow-sm">
                                    <i data-lucide="paperclip" class="w-4 h-4 text-airbnb-red"></i>
                                    {{ __('Download Attachment') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Conversation Thread --}}
            <div class="flex-grow p-8 space-y-8 overflow-y-auto">
                @foreach($ticket->conversations as $conv)
                    <div class="flex gap-4 {{ $conv->type == 2 ? 'flex-row' : 'flex-row' }}">
                        <div class="w-10 h-10 rounded-full {{ $conv->type == 2 ? 'bg-gray-900' : 'bg-airbnb-red' }} text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i data-lucide="{{ $conv->type == 2 ? 'headphones' : 'user' }}" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="font-bold text-gray-900">{{ $conv->type == 2 ? __('Support Agent') : $authUser->name }}</h4>
                                <span class="text-xs text-gray-400">• {{ \Carbon\Carbon::parse($conv->created_at)->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="p-5 {{ $conv->type == 2 ? 'bg-neutral-100' : 'bg-rose-50' }} rounded-2xl rounded-tl-none border {{ $conv->type == 2 ? 'border-neutral-200' : 'border-rose-100 shadow-sm' }}">
                                <div class="text-sm text-gray-700 leading-relaxed">
                                    {!! $conv->reply !!}
                                </div>
                                @if($conv->file)
                                    <div class="mt-3 pt-3 border-t border-neutral-200">
                                        <a href="{{ asset('assets/admin/img/support-ticket/' . $conv->file) }}" download 
                                           class="inline-flex items-center gap-2 text-[10px] font-bold text-airbnb-red hover:underline uppercase tracking-wider">
                                            <i data-lucide="download-cloud" class="w-3 h-3"></i>
                                            {{ __('Attachment') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Reply Form --}}
            @if(strtolower($ticket->status) != 'closed')
                <div class="p-8 border-t border-gray-100 bg-white rounded-b-2xl">
                    <form action="{{ route('user.support_ticket.reply', ['id' => $ticket->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div class="relative">
                                <textarea name="reply" rows="4" placeholder="{{ __('Type your reply here...') }}" required
                                          class="w-full bg-neutral-50 border border-neutral-200 rounded-2xl px-6 py-4 text-sm text-gray-900 focus:ring-2 focus:ring-rose-500 focus:bg-white outline-none transition-all placeholder:text-neutral-400"></textarea>
                                @error('reply') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="w-full sm:w-auto">
                                    <input type="file" name="file" id="file-reply" class="hidden">
                                    <label for="file-reply" class="flex items-center gap-2 px-4 py-2.5 bg-neutral-50 border border-neutral-100 rounded-xl text-xs font-bold text-neutral-500 hover:bg-neutral-100 transition-colors cursor-pointer group shadow-inner">
                                        <i data-lucide="paperclip" class="w-4 h-4 group-hover:text-airbnb-red"></i>
                                        {{ __('Attach Screenshot/ZIP') }}
                                    </label>
                                </div>
                                <button type="submit" class="w-full sm:w-auto bg-neutral-900 text-white px-10 py-3.5 rounded-xl font-bold hover:bg-black transition active:scale-95 shadow-xl flex items-center justify-center gap-2">
                                    <i data-lucide="corner-down-left" class="w-4 h-4"></i>
                                    {{ __('Send Reply') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="p-8 bg-neutral-100 rounded-b-2xl text-center">
                    <p class="text-xs font-bold text-neutral-500 uppercase tracking-widest">{{ __('This ticket is closed and cannot be replied to.') }}</p>
                </div>
            @endif
        </div>
    </div>
  </div>
</div>
@endsection
