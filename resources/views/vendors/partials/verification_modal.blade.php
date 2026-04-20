<style>
    /* Airbnb-style Modal Reset & Styling */
    #airbnb-verification-modal * {
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }
    
    #airbnb-verification-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        display: none; /* Hidden by default until JS checks storage */
        align-items: center;
        justify-content: center;
        z-index: 100000;
    }

    .airbnb-modal-content {
        background: #ffffff;
        width: 100%;
        max-width: 500px;
        border-radius: 24px;
        position: relative;
        padding: 40px;
        box-shadow: 0 8px 28px rgba(0,0,0,0.28);
        animation: airbnbModalSlideIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .airbnb-modal-close {
        position: absolute;
        top: 24px;
        right: 24px;
        background: transparent;
        border: none;
        cursor: pointer;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .airbnb-modal-close:hover {
        background: #f7f7f7;
    }

    .airbnb-modal-close svg {
        width: 16px;
        height: 16px;
        color: #222222;
        stroke-width: 2;
    }

    .airbnb-modal-title {
        color: #222222;
        font-size: 22px;
        font-weight: 600;
        margin: 0 0 16px 0;
        line-height: 1.2;
    }

    .airbnb-modal-body {
        color: #222222;
        font-size: 16px;
        font-weight: 300;
        line-height: 1.5;
        margin-bottom: 32px;
    }

    .airbnb-btn-primary {
        display: inline-block;
        background-color: #222222;
        color: #ffffff;
        font-size: 16px;
        font-weight: 600;
        padding: 14px 24px;
        border-radius: 8px;
        text-decoration: none;
        transition: transform 0.1s, background-color 0.2s;
        border: none;
        cursor: pointer;
    }

    .airbnb-btn-primary:hover {
        background-color: #000000;
        color: #ffffff;
        text-decoration: none;
    }

    .airbnb-btn-primary:active {
        transform: scale(0.96);
    }

    .airbnb-modal-link {
        display: block;
        margin-top: 32px;
        font-size: 14px;
        color: #222222;
        text-decoration: underline;
        font-weight: 400;
    }

    @keyframes airbnbModalSlideIn {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @media (max-width: 576px) {
        .airbnb-modal-content {
            margin: 16px;
            padding: 32px 24px;
        }
    }
</style>

<div id="airbnb-verification-modal">
    <div class="airbnb-modal-content">
        <button class="airbnb-modal-close" onclick="dismissVerificationModal()">
            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="display: block; fill: none; stroke: currentcolor; stroke-width: 3; overflow: visible;"><path d="m6 6 20 20"></path><path d="m26 6-20 20"></path></svg>
        </button>

        <h2 class="airbnb-modal-title">{{ __('Identitas Anda belum terverifikasi') }}</h2>
        <p class="airbnb-modal-body">
            {{ __('Anda harus memverifikasikan identitas agar bisa mendaftarkan perahu atau menerima pesanan di GoFishi. Urus hal itu sekarang untuk memulai.') }}
        </p>

        <a href="{{ route('vendor.verify.identity') }}" class="airbnb-btn-primary">
            {{ __('Dapatkan verifikasi') }}
        </a>

        <a href="#" class="airbnb-modal-link">
            <span>{{ __('Pelajari lebih lanjut tentang verifikasi identitas') }}</span>
        </a>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("airbnb-verification-modal");
        
        // Gunakan sessionStorage agar modal kembali muncul jika user membuka tab baru/restart browser
        if (!sessionStorage.getItem("gofishi_vendor_verified_dismissed")) {
            modal.style.display = "flex";
        }

        // Fungsi dismiss dari tombol silang
        window.dismissVerificationModal = function() {
            sessionStorage.setItem("gofishi_vendor_verified_dismissed", "true");
            modal.style.opacity = 0;
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        };
    });
</script>
