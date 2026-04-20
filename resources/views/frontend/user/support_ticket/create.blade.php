@extends('frontend.layout-airbnb')

@section('pageHeading')
  {{ __('Create Support Ticket') }}
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
            <h1 class="text-3xl font-bold text-gray-900">{{ __('Open New Ticket') }}</h1>
            <p class="text-gray-500 font-light">{{ __('Tell us more about your issue') }}</p>
          </div>
          <div class="flex gap-3">
            <a href="{{ route('user.support_ticket.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition shadow-sm font-semibold text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                {{ __('Cancel') }}
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Create Ticket Form --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-8 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5 text-airbnb-red"></i>
                    {{ __('Ticket Details') }}
                </h2>

                <form action="{{ route('user.support_ticket.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="space-y-1 group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-focus-within:text-airbnb-red transition-colors">{{ __('Subject / Topic') }}</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" placeholder="{{ __('What is your concern?') }}"
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 outline-none transition shadow-sm" required>
                            @error('subject') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1 opacity-60">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Your Email Address') }}</label>
                            <input type="email" name="email" value="{{ $authUser->email }}" readonly
                                   class="w-full bg-gray-50 border border-transparent rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed">
                        </div>

                        <div class="space-y-1 group">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-focus-within:text-airbnb-red transition-colors">{{ __('Detailed Description') }}</label>
                            <textarea name="description" rows="5" placeholder="{{ __('Describe your request in detail...') }}"
                                      class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-rose-500 outline-none transition shadow-sm" required>{{ old('description') }}</textarea>
                            @error('description') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        {{-- Attachment --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ __('Attachment (Optional)') }}</label>
                            <div class="p-4 bg-neutral-50 rounded-2xl border border-neutral-100 flex items-center justify-between">
                                <input type="file" name="attachment" 
                                       class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 transition-all cursor-pointer">
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">{{ __('Max') }} 20MB (.zip)</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="bg-neutral-900 text-white px-10 py-3.5 rounded-xl font-bold hover:bg-black transition active:scale-95 shadow-lg flex items-center gap-2">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            {{ __('Submit Ticket') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-28">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">{{ __('Support Tips') }}</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-500 shrink-0">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">{{ __('Describe your problem clearly to help our team assist you faster.') }}</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                            <i data-lucide="info" class="w-4 h-4"></i>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">{{ __('Response times may vary depending on the complexity of your request.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection
