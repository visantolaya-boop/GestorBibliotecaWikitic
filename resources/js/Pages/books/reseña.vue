<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import NavLink from '@/Components/NavLink.vue'; // Asegúrate de que la extensión sea correcta

const props = defineProps({
    reseña: Object
});

const statusStyles = {
    'Leyendo': 'bg-blue-100 text-blue-800 border-blue-200',
    'Terminado': 'bg-green-100 text-green-800 border-green-200',
    'Pendiente': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'Abandonado': 'bg-red-100 text-red-800 border-red-200',
    'default': 'bg-gray-100 text-gray-800 border-gray-200'
};

const getStatusClass = (estado) => {
    return statusStyles[estado] || statusStyles['default'];
};
</script>

<template>

    <Head title="Detalle de Reseña" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Reseña: <span class="text-blue-600">{{ reseña?.libros?.titulo || 'Sin título' }}</span>
                </h2>
                <NavLink :href="route('reseñas.editar', reseña?.id)"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
                    Editar Reseña
                </NavLink>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">

                    <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-1">
                                <p class="text-xs font-bold uppercase text-gray-400 tracking-wider">Periodo de lectura
                                </p>
                                <p class="text-gray-700 font-medium">
                                    {{ reseña?.inicio_lectura || '—' }} <span class="text-gray-300 mx-1">/</span> {{
                                    reseña?.final_lectura || '—' }}
                                </p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-xs font-bold uppercase text-gray-400 tracking-wider">Tiempo Invertido</p>
                                <p class="text-gray-700 font-medium">
                                    <span class="text-blue-600 font-bold">{{ reseña?.tiempo_lectura || 0 }}</span> días
                                </p>
                            </div>

                            <div class="space-y-1">
                                <p class="text-xs font-bold uppercase text-gray-400 tracking-wider">Estado actual</p>
                                <span
                                    :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border', getStatusClass(reseña?.estado)]">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-current"></span>
                                    {{ reseña?.estado || 'Desconocido' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="px-8 py-4 flex items-center space-x-2 border-b border-gray-50">
                        <p class="text-sm font-bold text-gray-500">Puntuación:</p>
                        <div class="flex text-green-400">
                            <svg v-for="i in 5" :key="i" class="w-5 h-5"
                                :class="i <= (reseña?.puntuacion || 0) ? 'fill-current' : 'text-gray-200 fill-current'"
                                viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>

                    <div class="p-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Análisis del Lector
                        </h3>
                        <div
                            class="prose max-w-none text-gray-600 leading-relaxed italic border-l-4 border-blue-100 pl-4">
                            {{ reseña?.reseña || 'El lector aún no ha escrito sus pensamientos sobre esta obra.' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>