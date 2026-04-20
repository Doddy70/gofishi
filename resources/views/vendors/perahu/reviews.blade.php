@extends('vendors.layout')

@section('content')
<div class="page-header">
  <h4 class="page-title">{{ __('Perahu Reviews') }}</h4>
  <ul class="breadcrumbs">
    <li class="nav-home">
      <a href="{{ route('vendor.dashboard') }}">
        <i class="flaticon-home"></i>
      </a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">{{ __('Perahu Reviews') }}</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">

    <!-- Smart AI Review Insights -->
    <div class="card mb-4" id="ai-insights-card" style="display: none; border-left: 4px solid #6777ef;">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title text-primary"><i class="fas fa-magic"></i> {{ __('Smart Review Insights') }}</h4>
        <button type="button" class="btn btn-sm btn-icon btn-round btn-danger p-0" onclick="$('#ai-insights-card').slideUp()" style="width: 25px; height: 25px;"><i class="fas fa-times"></i></button>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 border-right">
            <h6 class="text-uppercase text-muted fw-bold mb-3">{{ __('Status Sentimen') }}</h6>
            <div class="d-flex justify-content-between mb-1"><span><i class="fas fa-smile text-success"></i> Positif</span> <strong id="ai-sent-pos">0%</strong></div>
            <div class="progress mb-3"><div id="ai-prog-pos" class="progress-bar bg-success" style="width: 0%"></div></div>
            <div class="d-flex justify-content-between mb-1"><span><i class="fas fa-meh text-warning"></i> Netral</span> <strong id="ai-sent-neu">0%</strong></div>
            <div class="progress mb-3"><div id="ai-prog-neu" class="progress-bar bg-warning" style="width: 0%"></div></div>
            <div class="d-flex justify-content-between mb-1"><span><i class="fas fa-frown text-danger"></i> Negatif</span> <strong id="ai-sent-neg">0%</strong></div>
            <div class="progress mb-3"><div id="ai-prog-neg" class="progress-bar bg-danger" style="width: 0%"></div></div>
          </div>
          <div class="col-md-4 border-right">
            <h6 class="text-uppercase text-success fw-bold mb-3"><i class="fas fa-arrow-up"></i> {{ __('Keunggulan Utama') }}</h6>
            <ul id="ai-compliments" class="list-unstyled text-muted" style="font-size: 13px;"></ul>
            <h6 class="text-uppercase text-danger fw-bold mb-3 mt-4"><i class="fas fa-arrow-down"></i> {{ __('Titik Keluhan') }}</h6>
            <ul id="ai-complaints" class="list-unstyled text-muted" style="font-size: 13px;"></ul>
          </div>
          <div class="col-md-4">
            <h6 class="text-uppercase text-primary fw-bold mb-3"><i class="fas fa-lightbulb"></i> {{ __('Saran Strategis AI') }}</h6>
            <div class="alert alert-primary bg-primary text-white" role="alert" id="ai-advice" style="font-size: 13px;">
              Menunggu analisis...
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">{{ __('Ulasan Pelanggan') }}</h4>
        @php
            $aiStatusRaw = App\Models\BasicSettings\Basic::select('google_gemini_status')->first();
            $aiActive = ($aiStatusRaw && $aiStatusRaw->google_gemini_status == 1) || (env('GEMINI_API_KEY') && env('GEMINI_API_KEY') !== 'AIzaSyCV6ltmLwphJY07EZI8HOFRqLqZwSv8ghs');
        @endphp
        @if($aiActive)
        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-analyze-insights" onclick="generateBusinessInsights()">
            <i class="fas fa-brain mr-1"></i> {{ __('Analisis Sentimen Bisnis') }}
        </button>
        @endif
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped mt-3">
            <thead>
              <tr>
                <th>{{ __('Perahu') }}</th>
                <th>{{ __('Customer') }}</th>
                <th>{{ __('Rating') }}</th>
                <th>{{ __('Ulasan') }}</th>
                <th>{{ __('Tanggapan Anda') }}</th>
                <th>{{ __('Aksi') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($reviews as $review)
                <tr>
                  <td>{{ $review->room->room_content->first() ? $review->room->room_content->first()->title : 'Perahu' }}</td>
                  <td>{{ $review->user ? $review->user->username : 'Guest' }}</td>
                  <td>
                    <div class="text-warning">
                      @for($i=1; $i<=5; $i++)
                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                      @endfor
                    </div>
                  </td>
                  <td>{{ Str::limit($review->review, 50) }}</td>
                  <td>
                    @if($review->reply)
                      <span class="text-success">{{ Str::limit($review->reply, 50) }}</span>
                    @else
                      <span class="text-muted small"><i>{{ __('Belum ada tanggapan') }}</i></span>
                    @endif
                  </td>
                  <td>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#replyModal{{ $review->id }}">
                      {{ $review->reply ? __('Edit Tanggapan') : __('Beri Tanggapan') }}
                    </button>
                  </td>
                </tr>

                {{-- Reply Modal --}}
                <div class="modal fade" id="replyModal{{ $review->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">{{ __('Tanggapan Ulasan') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{ route('vendor.perahu.review.reply', $review->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                          <div class="form-group p-0">
                            <p class="mb-2"><strong>Ulasan User:</strong> <br> "{{ $review->review }}"</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label>{{ __('Tanggapan Anda') }}</label>
                                @php
                                    $aiStatusRaw = App\Models\BasicSettings\Basic::select('google_gemini_status')->first();
                                    $aiActive = ($aiStatusRaw && $aiStatusRaw->google_gemini_status == 1) || (env('GEMINI_API_KEY') && env('GEMINI_API_KEY') !== 'AIzaSyCV6ltmLwphJY07EZI8HOFRqLqZwSv8ghs');
                                @endphp
                                @if($aiActive)
                                    <button type="button" class="btn btn-primary btn-xs ai-reply-btn"
                                            onclick="generateAiReply({{ $review->id }}, '{{ $review->user ? $review->user->username : 'Pelanggan' }}', '{{ addslashes($review->review) }}')">
                                        <i class="fas fa-magic mr-1"></i> {{ __('✨ AI Reply') }}
                                    </button>
                                @endif
                            </div>
                            <textarea name="reply" id="reply_text_{{ $review->id }}" class="form-control" rows="5" placeholder="{{ __('Tulis tanggapan Anda di sini...') }}">{{ $review->reply }}</textarea>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Batal') }}</button>
                          <button type="submit" class="btn btn-success">{{ __('Simpan Tanggapan') }}</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @empty
                <tr>
                  <td colspan="6" class="text-center">{{ __('Belum ada ulasan.') }}</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          {{ $reviews->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
    function generateAiReply(reviewId, customerName, reviewText) {
        const btn = $(event.target).closest('.ai-reply-btn');
        const originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        $.ajax({
            url: "{{ route('vendor.smart_ai.generate_reply') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                customer_name: customerName,
                review_text: reviewText
            },
            success: function(response) {
                if (response.status === 'success') {
                    $(`#reply_text_${reviewId}`).val(response.reply);
                } else {
                    alert('Gagal membuat balasan AI.');
                }
            },
            error: function() {
                alert('Terjadi kesalahan koneksi ke server AI.');
            },
            complete: function() {
                btn.html(originalText).prop('disabled', false);
            }
        });
    }
</script>
<script>
    function generateBusinessInsights() {
        const btn = $('#btn-analyze-insights');
        const originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Menganalisis...').prop('disabled', true);

        $.ajax({
            url: "{{ route('vendor.perahu.review.analyze') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                if (response.status === 'success' && response.data) {
                    const d = response.data;
                    
                    // Update Progress Bars
                    $('#ai-sent-pos').text(d.sentiment_score.positive + '%');
                    $('#ai-prog-pos').css('width', d.sentiment_score.positive + '%');
                    $('#ai-sent-neu').text(d.sentiment_score.neutral + '%');
                    $('#ai-prog-neu').css('width', d.sentiment_score.neutral + '%');
                    $('#ai-sent-neg').text(d.sentiment_score.negative + '%');
                    $('#ai-prog-neg').css('width', d.sentiment_score.negative + '%');

                    // Update Lists
                    $('#ai-compliments').html( d.top_compliments.map(i => `<li><i class="fas fa-check text-success mr-2"></i> ${i}</li>`).join('') );
                    $('#ai-complaints').html( d.top_complaints.map(i => `<li><i class="fas fa-times text-danger mr-2"></i> ${i}</li>`).join('') );
                    
                    // Update Advice
                    $('#ai-advice').html(d.business_advice);

                    // Show Card smoothly
                    $('#ai-insights-card').slideDown();
                }
            },
            error: function(xhr) {
                let err = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error : 'Terjadi kesalahan sistem.';
                alert('Gagal mendownload analisis: ' + err);
            },
            complete: function() {
                btn.html(originalText).prop('disabled', false);
            }
        });
    }
</script>
@endsection
