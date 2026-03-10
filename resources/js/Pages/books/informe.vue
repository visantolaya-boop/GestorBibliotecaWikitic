<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
const props = defineProps({
    stats: Object
})

const getPorcentaje = (valor) => {
    if (props.stats.total_libros === 0) return 0;
    return (valor / props.stats.total_libros) * 100;
};
</script>

<template>
   <div class="p-6 bg-white rounded-xl shadow-lg">
        <h2 class="text-xl font-bold mb-4">Resumen de mi Biblioteca</h2>

        <div class="space-y-3 mb-8">
            <div v-for="(count, star) in stats.estrellas" :key="star" class="flex items-center">
                <span class="w-20 text-sm text-gray-600">{{ star }} estrellas</span>
                <div class="flex-1 h-4 bg-gray-200 rounded-full mx-4 overflow-hidden">
                    <div 
                        class="h-full bg-yellow-400 transition-all duration-500" 
                        :style="{ width: getPorcentaje(count) + '%' }"
                    ></div>
                </div>
                <span class="w-8 text-sm font-medium">{{ count }}</span>
            </div>
        </div>

        <hr class="my-6">

        <div class="grid grid-cols-3 gap-4 text-center">
            <div class="p-3 bg-green-50 rounded-lg">
                <p class="text-xs text-green-600 uppercase font-bold">Leídos</p>
                <p class="text-2xl font-black text-green-700">{{ stats.estados.terminados }}</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-lg">
                <p class="text-xs text-blue-600 uppercase font-bold">Leyendo</p>
                <p class="text-2xl font-black text-blue-700">{{ stats.estados.leyendo }}</p>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-600 uppercase font-bold">Pendientes</p>
                <p class="text-2xl font-black text-gray-700">{{ stats.estados.pendiente }}</p>
            </div>
            <div class="p-3 bg-red-50 rounded-lg">
                <p class="text-xs text-red-600 uppercase font-bold">Abandonados</p>
                <p class="text-2xl font-black text-red-700">{{ stats.estados.abandonados }}</p>
            </div>
        </div>
    </div>
</template>
