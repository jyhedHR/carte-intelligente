<style>
/* ══ TUNISIA MAP DROP BUTTON ══ */
.tn-drop-fab {
    position: fixed;
    bottom: 28px;
    left: 28px;              /* opposite side from the chat bot (usually bottom-right) */
    z-index: 9500;
    width: 68px;
    height: 84px;
    display: block;
    cursor: pointer;
    text-decoration: none;
    filter: drop-shadow(0 8px 16px rgba(0,0,0,0.35));
    animation: tnDropFloat 3s ease-in-out infinite;
}

.tn-drop-fab svg {
    width: 100%;
    height: 100%;
    display: block;
    overflow: visible;
    transition: transform 0.4s cubic-bezier(.34,1.6,.64,1);
    transform-origin: 50% 85%; /* wobble pivots near the bottom point */
}

@keyframes tnDropFloat {
    0%, 100% { transform: translateY(0); }
    50%      { transform: translateY(-5px); }
}

/* ── Hover: wobble + glow ── */
.tn-drop-fab:hover svg {
    animation: tnWobble 0.7s ease-in-out;
    filter: drop-shadow(0 0 10px rgba(220,38,38,0.55));
}

@keyframes tnWobble {
    0%   { transform: scale(1)    rotate(0deg); }
    25%  { transform: scale(1.12) rotate(-7deg); }
    50%  { transform: scale(1.14) rotate(6deg); }
    75%  { transform: scale(1.1)  rotate(-3deg); }
    100% { transform: scale(1.1)  rotate(0deg); }
}

/* ── Hover: expanding water ripples ── */
.tn-drop-fab::before,
.tn-drop-fab::after {
    content: '';
    position: absolute;
    left: 50%;
    top: 42%;
    width: 24px;
    height: 24px;
    border: 2px solid rgba(220,38,38,0.55);
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0.3);
    opacity: 0;
    pointer-events: none;
}

.tn-drop-fab:hover::before { animation: tnRipple 1s ease-out; }
.tn-drop-fab:hover::after  { animation: tnRipple 1s ease-out 0.3s; }

@keyframes tnRipple {
    0%   { transform: translate(-50%, -50%) scale(0.3); opacity: 0.8; }
    100% { transform: translate(-50%, -50%) scale(3.4); opacity: 0; }
}

/* ── Sparkle twinkle (always on, idle) ── */
.tn-spark {
    transform-box: fill-box;
    transform-origin: center;
    animation: tnTwinkle 2.2s ease-in-out infinite;
}
.tn-spark--2 { animation-delay: 0.7s; }
.tn-spark--3 { animation-delay: 1.3s; }

@keyframes tnTwinkle {
    0%, 100% { opacity: 0;   transform: scale(0.3) rotate(0deg); }
    50%      { opacity: 1;   transform: scale(1)   rotate(20deg); }
}

/* ── Tooltip ── */
.tn-drop-tooltip {
    position: absolute;
    left: 78px;
    top: 50%;
    transform: translateY(-50%);
    background: #1a1a2e;
    color: #f5f5f5;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s ease, left 0.2s ease;
    border: 1px solid rgba(220,38,38,0.4);
    font-family: var(--font-body, sans-serif);
}

.tn-drop-fab:hover .tn-drop-tooltip {
    opacity: 1;
    left: 82px;
}

@media (max-width: 768px) {
    .tn-drop-fab {
        bottom: 18px;
        left: 18px;
        width: 56px;
        height: 70px;
    }
    .tn-drop-tooltip { display: none; }
}
</style>

<!-- ══ TUNISIA MAP DROP BUTTON — links to the heritage map page ══ -->
<a href="{{ route('heritage-map') }}" class="tn-drop-fab" title="Carte du patrimoine - Cliquez pour explorer">
    <svg viewBox="0 0 100 130" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <!-- Glass fill for the drop -->
            <radialGradient id="tnGlass" cx="35%" cy="30%" r="75%">
                <stop offset="0%"  stop-color="#ffffff" stop-opacity="0.30"/>
                <stop offset="55%" stop-color="#ffffff" stop-opacity="0.08"/>
                <stop offset="100%" stop-color="#ffffff" stop-opacity="0.03"/>
            </radialGradient>

            <clipPath id="tnDropClip">
                <path d="M50 126 C27 100 8 75 8 45 A42 42 0 1 1 92 45 C92 75 73 100 50 126 Z"/>
            </clipPath>
        </defs>

        <!--
            ══ YOUR PHOTO GOES HERE ══
            Replace the href below with the path to your own image
            (a real map photo, a scan, whatever you like). It's clipped
            to the drop shape automatically and cropped to fill it via
            preserveAspectRatio="xMidYMid slice" (same behavior as CSS
            object-fit: cover).
        -->
        <image href="{{ Vite::asset('resources/assets/images/tuniflag.png') }}"
               x="0" y="0" width="100" height="130"
               preserveAspectRatio="xMidYMid slice"
               clip-path="url(#tnDropClip)"
               opacity="0.95"/>

        <!-- Water drop outline, glass on top of the photo -->
        <path d="M50 126 C27 100 8 75 8 45 A42 42 0 1 1 92 45 C92 75 73 100 50 126 Z"
              fill="url(#tnGlass)" stroke="rgba(255,255,255,0.6)" stroke-width="1.5"/>

        <!-- glossy highlight -->
        <ellipse cx="34" cy="34" rx="10" ry="14" fill="#ffffff" opacity="0.18"/>

        <!-- ══ Sparkles ══ -->
        <path class="tn-spark tn-spark--1" transform="translate(70,24)"
              d="M0 -8 Q2 -2 8 0 Q2 2 0 8 Q-2 2 -8 0 Q-2 -2 0 -8 Z"
              fill="#ffffff"/>
        <path class="tn-spark tn-spark--2" transform="translate(24,50) scale(0.6)"
              d="M0 -8 Q2 -2 8 0 Q2 2 0 8 Q-2 2 -8 0 Q-2 -2 0 -8 Z"
              fill="#ffffff"/>
        <path class="tn-spark tn-spark--3" transform="translate(62,90) scale(0.5)"
              d="M0 -8 Q2 -2 8 0 Q2 2 0 8 Q-2 2 -8 0 Q-2 -2 0 -8 Z"
              fill="#ffffff"/>
    </svg>
    <span class="tn-drop-tooltip">Carte du patrimoine</span>
</a>
