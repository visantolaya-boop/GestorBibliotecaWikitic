<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
defineProps({
    libros: Array
})
</script>

<template>
    <Head title="Lista Libros" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Lista de libros
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <ul v-for="libro in libros" :key="libro.id">
                            <li >
                                <strong>Id:</strong> {{ libro.id }} 
                            </li>
                            <li >
                                <strong>Titulo:</strong> {{ libro.titulo }} 
                            </li>
                            <li >
                                <strong>Autor:</strong> {{ libro.autor }}
                            </li>
                            <br>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
