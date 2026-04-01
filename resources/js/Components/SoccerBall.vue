<template>
    <div class="soccer-ball-wrapper" :style="{ width: size + 'px', height: size + 'px' }">
        <svg
            :width="size"
            :height="size"
            viewBox="0 0 100 100"
            class="soccer-ball"
            xmlns="http://www.w3.org/2000/svg"
        >
            <!-- Ball body -->
            <circle cx="50" cy="50" r="48" fill="#ffffff" stroke="#1a1a1a" stroke-width="1.5" />

            <!-- Pentagon patches (dark) -->
            <polygon points="50,15 61,26 57,40 43,40 39,26" fill="#1a1a1a" />
            <polygon points="78,38 85,52 77,63 65,58 65,44" fill="#1a1a1a" />
            <polygon points="70,76 60,85 47,82 44,69 56,64" fill="#1a1a1a" />
            <polygon points="22,38 35,44 35,58 23,63 15,52" fill="#1a1a1a" />
            <polygon points="30,76 44,69 56,64 53,82 40,85" fill="#1a1a1a" />

            <!-- Hexagon outlines -->
            <line x1="39" y1="26" x2="35" y2="44" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="61" y1="26" x2="65" y2="44" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="57" y1="40" x2="65" y2="44" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="43" y1="40" x2="35" y2="44" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="77" y1="63" x2="70" y2="76" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="65" y1="58" x2="56" y2="64" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="35" y1="58" x2="44" y2="69" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="23" y1="63" x2="30" y2="76" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="50" y1="15" x2="50" y2="3" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="78" y1="38" x2="87" y2="28" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="22" y1="38" x2="13" y2="28" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="85" y1="52" x2="97" y2="52" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="15" y1="52" x2="3" y2="52" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="60" y1="85" x2="62" y2="97" stroke="#1a1a1a" stroke-width="1.2" />
            <line x1="40" y1="85" x2="38" y2="97" stroke="#1a1a1a" stroke-width="1.2" />

            <!-- Shine highlight -->
            <ellipse cx="35" cy="25" rx="10" ry="6" fill="white" opacity="0.4" transform="rotate(-30, 35, 25)" />
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
    animation: spinBounce 3s ease-in-out infinite;
    filter: drop-shadow(0 4px 12px rgba(250, 204, 21, 0.3));
}

@keyframes spinBounce {
    0% {
        transform: rotate(0deg) translateY(0px);
    }
    25% {
        transform: rotate(90deg) translateY(-8px);
    }
    50% {
        transform: rotate(180deg) translateY(0px);
    }
    75% {
        transform: rotate(270deg) translateY(-8px);
    }
    100% {
        transform: rotate(360deg) translateY(0px);
    }
}
</style>
