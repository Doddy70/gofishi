@php
  $basicSettings = \App\Models\BasicSettings\Basic::select('google_gemini_status')->first();
@endphp

@if(optional($basicSettings)->google_gemini_status == 1)
<div x-data="{ 
    open: false, 
    messages: [
        { role: 'assistant', content: 'Halo! Saya asisten AI Gofishi. Ada yang bisa saya bantu cari perahu hari ini?' }
    ],
    userInput: '',
    loading: false,
    async sendMessage() {
        if (!this.userInput.trim()) return;
        
        const userMsg = this.userInput;
        this.messages.push({ role: 'user', content: userMsg });
        this.userInput = '';
        this.loading = true;
        
        try {
            const response = await fetch('{{ route('frontend.perahu.ai_search_chat') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: userMsg })
            });
            const data = await response.json();
            this.messages.push({ role: 'assistant', content: data.reply });
        } catch (error) {
            this.messages.push({ role: 'assistant', content: 'Maaf, terjadi kesalahan. Silakan coba lagi.' });
        } finally {
            this.loading = false;
            this.$nextTick(() => {
                const container = this.$refs.chatContainer;
                container.scrollTop = container.scrollHeight;
            });
        }
    }
}" class="fixed bottom-6 right-6 z-50">
    
    <!-- Chat Bubble Button -->
    <button @click="open = !open" 
            class="w-14 h-14 bg-airbnb-red rounded-full flex items-center justify-center text-white shadow-2xl hover:scale-110 transition-transform duration-200">
        <template x-if="!open">
            <i data-lucide="sparkle" class="w-7 h-7"></i>
        </template>
        <template x-if="open">
            <i data-lucide="x" class="w-7 h-7"></i>
        </template>
    </button>

    <!-- Chat Window -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-10 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-95"
         class="absolute bottom-20 right-0 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 flex flex-col overflow-hidden">
        
        <!-- Header -->
        <div class="bg-airbnb-red p-4 text-white flex items-center space-x-3">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                <i data-lucide="bot" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="font-bold">Smart Assistant</h4>
                <p class="text-xs text-white/80">Powered by Gofishi AI</p>
            </div>
        </div>

        <!-- Messages Container -->
        <div x-ref="chatContainer" class="flex-grow h-80 overflow-y-auto p-4 space-y-4 no-scrollbar bg-gray-50">
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="msg.role === 'user' 
                        ? 'bg-airbnb-red text-white rounded-2xl rounded-tr-none px-4 py-2 max-w-[85%] shadow-sm' 
                        : 'bg-white text-gray-800 rounded-2xl rounded-tl-none px-4 py-2 max-w-[85%] border border-gray-100 shadow-sm'"
                         class="text-sm">
                        <span x-text="msg.content"></span>
                    </div>
                </div>
            </template>
            
            <template x-if="loading">
                <div class="flex justify-start">
                    <div class="bg-gray-200 rounded-2xl rounded-tl-none px-4 py-2 flex space-x-1">
                        <div class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce"></div>
                        <div class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce [animation-delay:-.3s]"></div>
                        <div class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce [animation-delay:-.5s]"></div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-gray-100">
            <div class="relative flex items-center">
                <input type="text" 
                       x-model="userInput" 
                       @keydown.enter="sendMessage"
                       placeholder="Cari perahu di Jakarta..." 
                       class="w-full pl-4 pr-12 py-3 bg-gray-100 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-airbnb-red/20 border-none">
                <button @click="sendMessage" 
                        class="absolute right-2 p-2 text-airbnb-red hover:bg-white rounded-full transition">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
