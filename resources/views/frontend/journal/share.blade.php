 <div class="modal fade" id="socialMediaModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
     <div class="modal-content border-0 rounded-3xl shadow-2xl">
       <div class="modal-header border-b border-gray-100 p-6 flex justify-between items-center bg-white rounded-t-3xl">
         <h5 class="text-xl font-bold text-gray-900 m-0">{{ __('Bagikan Artikel Ini') }}</h5>
         <button type="button" class="btn-close text-gray-400 hover:text-gray-900 transition" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body p-6 bg-white rounded-b-3xl">
         
         {{-- Social Media Grid --}}
         <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
           {{-- Facebook --}}
           <a class="flex items-center gap-4 p-4 rounded-2xl border border-gray-200 hover:border-blue-500 hover:bg-blue-50 hover:shadow-md transition duration-300 group" 
              href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&src=sdkpreparse" target="_blank">
             <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition duration-300">
               <i class="fab fa-facebook-f text-xl"></i>
             </div>
             <span class="font-semibold text-gray-700 group-hover:text-blue-700">{{ $keywords['Facebook'] ?? 'Facebook' }}</span>
           </a>

           {{-- Linkedin --}}
           <a class="flex items-center gap-4 p-4 rounded-2xl border border-gray-200 hover:border-blue-700 hover:bg-blue-50 hover:shadow-md transition duration-300 group" 
              href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}" target="_blank">
             <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center group-hover:bg-blue-700 group-hover:text-white transition duration-300">
               <i class="fab fa-linkedin-in text-xl"></i>
             </div>
             <span class="font-semibold text-gray-700 group-hover:text-blue-800">{{ $keywords['Linkedin'] ?? 'Linkedin' }}</span>
           </a>

           {{-- Twitter --}}
           <a class="flex items-center gap-4 p-4 rounded-2xl border border-gray-200 hover:border-gray-900 hover:bg-gray-100 hover:shadow-md transition duration-300 group" 
              href="https://twitter.com/intent/tweet?text={{ url()->current() }}" target="_blank">
             <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-800 flex items-center justify-center group-hover:bg-gray-900 group-hover:text-white transition duration-300">
               <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
             </div>
             <span class="font-semibold text-gray-700 group-hover:text-gray-900">{{ $keywords['Twitter'] ?? 'X (Twitter)' }}</span>
           </a>

           {{-- Whatsapp --}}
           <a class="flex items-center gap-4 p-4 rounded-2xl border border-gray-200 hover:border-green-500 hover:bg-green-50 hover:shadow-md transition duration-300 group" 
              href="whatsapp://send?text={{ urlencode(url()->current()) }}" target="_blank">
             <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition duration-300">
               <i class="fab fa-whatsapp text-xl"></i>
             </div>
             <span class="font-semibold text-gray-700 group-hover:text-green-700">{{ $keywords['Whatsapp'] ?? 'Whatsapp' }}</span>
           </a>

           {{-- SMS --}}
           <a class="flex items-center gap-4 p-4 rounded-2xl border border-gray-200 hover:border-yellow-500 hover:bg-yellow-50 hover:shadow-md transition duration-300 group" 
              href="sms:?body={{ url()->current() }}" target="_blank">
             <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center group-hover:bg-yellow-500 group-hover:text-white transition duration-300">
               <i class="fas fa-sms text-xl"></i>
             </div>
             <span class="font-semibold text-gray-700 group-hover:text-yellow-700">{{ $keywords['SMS'] ?? 'SMS' }}</span>
           </a>

           {{-- Email --}}
           <a class="flex items-center gap-4 p-4 rounded-2xl border border-gray-200 hover:border-red-500 hover:bg-red-50 hover:shadow-md transition duration-300 group" 
              href="mailto:?subject=Pilihan Artikel GoFishi&amp;body=Lihat artikel menarik ini: {{ urlencode(url()->current()) }}" target="_blank">
             <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition duration-300">
               <i class="fas fa-envelope text-xl"></i>
             </div>
             <span class="font-semibold text-gray-700 group-hover:text-red-700">{{ $keywords['Mail'] ?? 'Email' }}</span>
           </a>
         </div>

         {{-- Copy Link Section --}}
         <div class="mt-8 pt-6 border-t border-gray-100">
           <label class="text-xs font-bold text-gray-500 uppercase tracking-widest block mb-3">{{ __('Salin Tautan') }}</label>
           <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden bg-gray-50 focus-within:border-gray-900 focus-within:ring-1 focus-within:ring-gray-900 transition duration-200">
             <input type="text" readonly value="{{ url()->current() }}" class="w-full bg-transparent border-none text-[15px] font-medium text-gray-600 px-5 py-3.5 focus:outline-none focus:ring-0" id="shareUrlInput">
             <button onclick="copyShareUrl()" class="px-6 py-3.5 text-sm font-bold text-gray-900 hover:text-white bg-white hover:bg-gray-900 border-l border-gray-300 transition duration-200 whitespace-nowrap" id="copyBtn">
               {{ __('Salin') }}
             </button>
           </div>
         </div>

       </div>
     </div>
   </div>
 </div>

 <script>
 function copyShareUrl() {
     var copyText = document.getElementById("shareUrlInput");
     var btn = document.getElementById("copyBtn");
     
     copyText.select();
     copyText.setSelectionRange(0, 99999);
     navigator.clipboard.writeText(copyText.value).then(() => {
         let originalText = btn.innerText;
         btn.innerText = "Tersalin!";
         btn.classList.add("bg-green-600", "text-white", "hover:bg-green-700");
         btn.classList.remove("bg-white", "hover:bg-gray-900", "text-gray-900", "hover:text-white");
         
         setTimeout(() => {
             btn.innerText = "Salin";
             btn.classList.remove("bg-green-600", "text-white", "hover:bg-green-700");
             btn.classList.add("bg-white", "hover:bg-gray-900", "text-gray-900", "hover:text-white");
         }, 3000);
     });
 }
 </script>
