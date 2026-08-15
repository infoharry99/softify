<!-- Global Talentifyy Brand Logo Preloader Overlay -->
<div id="globalAppPreloader" class="global-app-preloader">
    <div class="preloader-content-box">
        <div class="logo-wrapper">
            <img src="{{ asset('images/logo.png') }}" alt="Talentifyy Logo" class="preloader-logo-img">
            <div class="logo-glow-ring"></div>
        </div>
        <div class="preloader-brand-title">TALENTIFYY</div>
        <div class="preloader-subtext">Enterprise Recruitment & HR Management</div>
        <div class="preloader-progress-track">
            <div class="preloader-progress-bar"></div>
        </div>
    </div>
</div>

<style>
    .global-app-preloader {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255, 255, 255, 0.65);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.35s ease, visibility 0.35s ease;
    }
    .global-app-preloader.fade-out {
        opacity: 0;
        visibility: hidden;
    }
    .preloader-content-box {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: rgba(255, 255, 255, 0.88);
        padding: 28px 48px;
        border-radius: 20px;
        border: 1.5px solid #9ee5d4;
        box-shadow: 0 20px 40px -15px rgba(0, 168, 132, 0.18);
    }
    .logo-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 14px;
    }
    .preloader-logo-img {
        height: 64px;
        width: auto;
        object-fit: contain;
        position: relative;
        z-index: 2;
        animation: logoBouncePulse 1.6s ease-in-out infinite;
    }
    .logo-glow-ring {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 70px; height: 70px;
        background: rgba(0, 168, 132, 0.15);
        border-radius: 50%;
        z-index: 1;
        animation: ringPulse 1.6s ease-in-out infinite;
    }
    .preloader-brand-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #00a884;
        letter-spacing: -0.4px;
        margin-bottom: 2px;
    }
    .preloader-subtext {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 16px;
    }
    .preloader-progress-track {
        width: 140px;
        height: 4px;
        background: #e2e8f0;
        border-radius: 999px;
        overflow: hidden;
        position: relative;
    }
    .preloader-progress-bar {
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 45%;
        background: linear-gradient(90deg, #00a884, #008f70);
        border-radius: 999px;
        animation: lineLoader 1.3s infinite ease-in-out;
    }

    @keyframes logoBouncePulse {
        0%, 100% { transform: scale(0.94); }
        50% { transform: scale(1.08); }
    }
    @keyframes ringPulse {
        0%, 100% { transform: translate(-50%, -50%) scale(0.9); opacity: 0.4; }
        50% { transform: translate(-50%, -50%) scale(1.4); opacity: 0.9; }
    }
    @keyframes lineLoader {
        0% { left: -40%; width: 30%; }
        50% { left: 35%; width: 50%; }
        100% { left: 100%; width: 30%; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const preloader = document.getElementById('globalAppPreloader');
        if (preloader) {
            setTimeout(function() {
                preloader.classList.add('fade-out');
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 350);
            }, 250);
        }
    });
</script>
