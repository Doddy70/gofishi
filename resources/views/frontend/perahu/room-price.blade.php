 @if (!empty($roomContent->price_day_1) || !empty($roomContent->price_day_2) || !empty($roomContent->price_day_3))
   <ul class="list-group custom-radio">
     @if (!empty($roomContent->price_day_1))
       <li>
         <input class="input-radio day-package" type="radio" name="day_package" id="day_1" value="1"
           data-meet="{{ $roomContent->meet_time_day_1 }}" data-return="{{ $roomContent->return_time_day_1 }}" checked>
         <label class="form-radio-label" for="day_1">
           <span> {{ __('1 Hari') }}</span>
           <span class="qty"> {{ symbolPrice($roomContent->price_day_1) }}</span>
         </label>
       </li>
     @endif
     @if (!empty($roomContent->price_day_2))
       <li>
         <input class="input-radio day-package" type="radio" name="day_package" id="day_2" value="2"
           data-meet="{{ $roomContent->meet_time_day_2 }}" data-return="{{ $roomContent->return_time_day_2 }}">
         <label class="form-radio-label" for="day_2">
           <span> {{ __('2 Hari') }}</span>
           <span class="qty"> {{ symbolPrice($roomContent->price_day_2) }}</span>
         </label>
       </li>
     @endif
     @if (!empty($roomContent->price_day_3))
       <li>
         <input class="input-radio day-package" type="radio" name="day_package" id="day_3" value="3"
           data-meet="{{ $roomContent->meet_time_day_3 }}" data-return="{{ $roomContent->return_time_day_3 }}">
         <label class="form-radio-label" for="day_3">
           <span> {{ __('3 Hari') }}</span>
           <span class="qty"> {{ symbolPrice($roomContent->price_day_3) }}</span>
         </label>
       </li>
     @endif
   </ul>
 @else
   <h6 class="mt-2 text-warning ps-3 pb-2">{{ __('Slot pemesanan belum tersedia') }}</h6>
 @endif
