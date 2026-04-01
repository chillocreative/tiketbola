<template>
    <div class="soccer-ball-wrapper" :style="{ width: size + 'px', height: size + 'px' }">
        <svg
            :width="size"
            :height="size"
            viewBox="0 0 200 200"
            class="soccer-ball"
            xmlns="http://www.w3.org/2000/svg"
        >
            <defs>
                <!-- 3D sphere gradient -->
                <radialGradient id="ballGrad" cx="40%" cy="35%" r="55%" fx="38%" fy="32%">
                    <stop offset="0%" stop-color="#ffffff" />
                    <stop offset="45%" stop-color="#f0f0f0" />
                    <stop offset="75%" stop-color="#d4d4d4" />
                    <stop offset="100%" stop-color="#a0a0a0" />
                </radialGradient>
                <!-- Dark patch gradient for 3D feel -->
                <radialGradient id="patchGrad" cx="40%" cy="35%" r="60%">
                    <stop offset="0%" stop-color="#3a3a3a" />
                    <stop offset="100%" stop-color="#1a1a1a" />
                </radialGradient>
                <!-- Highlight shine -->
                <radialGradient id="shineGrad" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#ffffff" stop-opacity="0.9" />
                    <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
                </radialGradient>
                <!-- Shadow beneath -->
                <radialGradient id="shadowGrad" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#000000" stop-opacity="0.3" />
                    <stop offset="100%" stop-color="#000000" stop-opacity="0" />
                </radialGradient>
            </defs>

            <!-- Drop shadow ellipse -->
            <ellipse class="ball-shadow" cx="100" cy="190" rx="40" ry="8" fill="url(#shadowGrad)" />

            <!-- Main ball circle -->
            <circle cx="100" cy="96" r="88" fill="url(#ballGrad)" />

            <!-- Panel seam lines (hexagon edges) -->
            <g stroke="#555" stroke-width="1.2" fill="none" stroke-linejoin="round">
                <!-- Top center hex -->
                <polygon points="100,12 120,28 115,52 85,52 80,28" />
                <!-- Top-right hex -->
                <polygon points="120,28 148,22 168,44 160,66 135,60 115,52" />
                <!-- Right hex -->
                <polygon points="168,44 184,70 180,98 160,106 148,82 160,66" />
                <!-- Bottom-right hex -->
                <polygon points="160,106 180,98 185,126 168,150 145,142 140,118" />
                <!-- Bottom hex -->
                <polygon points="145,142 168,150 155,174 118,178 110,156 128,140" />
                <!-- Bottom-center hex -->
                <polygon points="110,156 118,178 82,178 72,156 90,140" />
                <!-- Bottom-left hex -->
                <polygon points="72,156 82,178 45,174 32,150 55,142 60,118" />
                <!-- Left hex -->
                <polygon points="32,150 15,126 20,98 40,106 60,118 55,142" />
                <!-- Top-left hex -->
                <polygon points="20,98 15,70 32,44 52,22 80,28 85,52 68,66 40,72 40,106" />
                <!-- Inner connections -->
                <polygon points="85,52 115,52 135,60 148,82 140,118 128,140 110,156 90,140 60,118 40,106 40,72 68,66" />
            </g>

            <!-- Black pentagon patches -->
            <!-- Top pentagon -->
            <polygon points="100,16 117,30 112,50 88,50 83,30" fill="url(#patchGrad)" stroke="#222" stroke-width="0.8" />

            <!-- Upper-right pentagon -->
            <polygon points="165,48 180,72 172,94 155,86 150,62" fill="url(#patchGrad)" stroke="#222" stroke-width="0.8" />

            <!-- Lower-right pentagon -->
            <polygon points="178,120 165,148 142,142 138,118 158,108" fill="url(#patchGrad)" stroke="#222" stroke-width="0.8" />

            <!-- Bottom pentagon -->
            <polygon points="115,172 85,172 74,152 92,140 108,140 126,152" fill="url(#patchGrad)" stroke="#222" stroke-width="0.8" />

            <!-- Lower-left pentagon -->
            <polygon points="35,148 22,120 42,108 62,118 58,142" fill="url(#patchGrad)" stroke="#222" stroke-width="0.8" />

            <!-- Upper-left pentagon -->
            <polygon points="20,72 35,48 50,62 48,86 28,94" fill="url(#patchGrad)" stroke="#222" stroke-width="0.8" />

            <!-- White panel edge highlights (subtle) -->
            <g stroke="rgba(255,255,255,0.15)" stroke-width="2" fill="none">
                <line x1="88" y1="50" x2="68" y2="66" />
                <line x1="112" y1="50" x2="135" y2="60" />
                <line x1="155" y1="86" x2="148" y2="82" />
                <line x1="138" y1="118" x2="128" y2="140" />
                <line x1="92" y1="140" x2="74" y2="152" />
                <line x1="62" y1="118" x2="58" y2="142" />
                <line x1="48" y1="86" x2="40" y2="72" />
            </g>

            <!-- Specular highlight -->
            <ellipse cx="72" cy="58" rx="28" ry="18" fill="url(#shineGrad)" opacity="0.55" transform="rotate(-25, 72, 58)" />
            <!-- Smaller secondary shine -->
            <ellipse cx="62" cy="48" rx="10" ry="6" fill="white" opacity="0.35" transform="rotate(-25, 62, 48)" />

            <!-- Rim edge shadow (bottom-right) -->
            <circle cx="100" cy="96" r="87" fill="none" stroke="rgba(0,0,0,0.08)" stroke-width="3" />
        </svg>
    </div>
</template>

<script setup>
defineProps({
    size: {
        type: Number,
        default: 64,
    },
});
</script>

<style scoped>
.soccer-ball-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.soccer-ball {
    animation: floatSpin 4s ease-in-out infinite;
    filter: drop-shadow(0 6px 20px rgba(250, 204, 21, 0.25));
}

.ball-shadow {
    animation: shadowPulse 4s ease-in-out infinite;
    transform-origin: center;
}

@keyframes floatSpin {
    0% {
        transform: translateY(0px) rotate(0deg);
    }
    15% {
        transform: translateY(-10px) rotate(18deg);
    }
    30% {
        transform: translateY(-6px) rotate(10deg);
    }
    50% {
        transform: translateY(-14px) rotate(-5deg);
    }
    70% {
        transform: translateY(-4px) rotate(-12deg);
    }
    85% {
        transform: translateY(-10px) rotate(-6deg);
    }
    100% {
        transform: translateY(0px) rotate(0deg);
    }
}

@keyframes shadowPulse {
    0%, 100% {
        opacity: 0.6;
        rx: 40;
        ry: 8;
    }
    50% {
        opacity: 0.3;
        rx: 30;
        ry: 5;
    }
}
</style>
