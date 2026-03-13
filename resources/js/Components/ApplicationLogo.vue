<script setup>
defineProps({
    className: {
        type: String,
        default: 'h-24 w-auto' // Este será el tamaño por defecto si no pones nada
    }
});
</script>
<template>
    <img 
        src="/storage/imagenes/logo/logoWikitic.png"
        alt="Logo" 
        :class="className" 
    />
</template>
