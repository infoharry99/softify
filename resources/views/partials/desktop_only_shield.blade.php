<!-- Client-Side Desktop Device Shield Overlay -->
<div id="desktopOnlyClientShield" style="
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: #0f172a;
    z-index: 99999999;
    align-items: center;
    justify-content: center;
    padding: 24px;
    box-sizing: border-box;
">
    <div style="
        background: #ffffff;
        width: 100%;
        max-width: 440px;
        border-radius: 20px;
        padding: 32px 24px;
        text-align: center;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    ">
        <div style="
            width: 76px;
            height: 76px;
            background: #fee2e2;
            color: #ef4444;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 18px;
        ">
            <i class="fa-solid fa-desktop"></i>
        </div>

        <div style="
            display: inline-block;
            background: #fee2e2;
            color: #b91c1c;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 12px;
            text-transform: uppercase;
        ">
            Desktop Only Access
        </div>

        <h2 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 10px;">
            Please Switch to Desktop
        </h2>

        <p style="font-size: 0.9rem; color: #64748b; line-height: 1.5; margin-bottom: 20px;">
            Employees are strictly prohibited from using this platform from mobile phones. Please log in from a <strong>desktop or laptop computer</strong>.
        </p>

        <div style="
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.82rem;
            color: #475569;
            margin-bottom: 22px;
            text-align: left;
        ">
            <i class="fa-solid fa-triangle-exclamation" style="color: #f59e0b; margin-right: 6px;"></i>
            <strong>Mobile screen detected.</strong> Switch to your office workstation to view your dashboard, attendance, and tasks.
        </div>

        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" style="
                width: 100%;
                background: #ef4444;
                color: #ffffff;
                border: none;
                padding: 12px 20px;
                border-radius: 10px;
                font-size: 0.92rem;
                font-weight: 700;
                cursor: pointer;
            ">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
            </button>
        </form>
    </div>
</div>

<script>
(function() {
    function enforceDesktopOnly() {
        const isMobileUA = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile/i.test(navigator.userAgent);
        const hasTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
        const isNarrow = (window.innerWidth < 1024) || (screen.width < 1024);

        const shield = document.getElementById('desktopOnlyClientShield');
        if (!shield) return;

        if (isMobileUA || (hasTouch && isNarrow)) {
            shield.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } else {
            shield.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    window.addEventListener('DOMContentLoaded', enforceDesktopOnly);
    window.addEventListener('resize', enforceDesktopOnly);
    enforceDesktopOnly();
})();
</script>
