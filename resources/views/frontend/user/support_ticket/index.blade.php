@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Support Tickets') }}
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
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Support Help Center') }}</h1>
            <p class="text-gray-500 font-light">{{ __('Track your inquiries and support requests') }}</p>
          </div>
          <div class="flex gap-3">
            <a href="{{ route('user.support_ticket.create') }}" class="flex items-center gap-2 px-6 py-2.5 bg-airbnb-red text-white rounded-xl hover:bg-rose-600 transition shadow-md font-semibold text-sm">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                {{ __('Open New Ticket') }}
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Tickets Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="message-square" class="w-5 h-5 text-airbnb-red"></i>
                        {{ __('Active Conversations') }}
                    </h2>
                </div>

                @if(count($collection) == 0)
                    <div class="py-16 text-center">
                        <div class="w-20 h-20 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-6 text-neutral-200">
                            <i data-lucide="help-circle" class="w-10 h-10"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('No Support Tickets Yet') }}</h3>
                        <p class="text-gray-500 font-light mb-8 max-w-xs mx-auto">{{ __('If you have any questions or issues, feel free to open a new support ticket.') }}</p>
                        <a href="{{ route('user.support_ticket.create') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-gray-900 text-white rounded-xl font-bold hover:bg-black transition active:scale-95 shadow-md">
                            {{ __('Get Help Now') }}
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($collection as $ticket)
                            <div class="flex items-center justify-between p-5 hover:bg-neutral-50 rounded-2xl border border-neutral-100 hover:border-neutral-200 transition group cursor-pointer" 
                                 onclick="window.location.href='{{ route('user.support_ticket.message', ['id' => $ticket->id]) }}'">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-colors">
                                        <i data-lucide="ticket" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-3 mb-1">
                                            <p class="font-bold text-gray-900">#{{ $ticket->id }} - {{ Str::limit($ticket->subject, 35) }}</p>
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
                                        </div>
                                        <p class="text-xs text-gray-500 font-light">
                                            {{ __('Last interaction') }}: {{ \Carbon\Carbon::parse($ticket->last_message)->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <i data-lucide="chevron-right" class="w-5 h-5 text-neutral-300 group-hover:text-airbnb-red transition-colors"></i>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-28">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">{{ __('Support Navigation') }}</h3>
                </div>
                <nav class="p-2 flex flex-col gap-1">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 transition font-medium text-sm">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> {{ __('Dashboard Overview') }}
                    </a>
                    <a href="{{ route('user.support_ticket.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-50 text-airbnb-red transition font-bold text-sm">
                        <i data-lucide="help-circle" class="w-4 h-4"></i> {{ __('Support Tickets') }}
                    </a>
                    <hr class="my-2 mx-4 border-neutral-100">
                    <a href="{{ route('user.logout') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-red-50 text-red-600 transition font-bold text-sm">
                        <i data-lucide="log-out" class="w-4 h-4"></i> {{ __('Logout') }}
                    </a>
                </nav>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection
