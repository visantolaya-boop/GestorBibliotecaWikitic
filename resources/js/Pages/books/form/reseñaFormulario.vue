<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import Input from '@/Components/Input.vue';
import Label from '@/Components/Label.vue'; // Si no tienes este componente, usa <label> con clases

const props = defineProps({
    reseña: Object
});

const form = useForm({
    inicio_lectura: props.reseña.inicio_lectura,
    final_lectura: props.reseña.final_lectura,
    tiempo_lectura: props.reseña.tiempo_lectura,
    estado: props.reseña.estado,
    puntuacion: props.reseña.puntuacion,
    reseña: props.reseña.reseña,
});

const actualizar = () => {
    form.put(route('reseñas.update', props.reseña.id));
};

const volverAtras = () => {
    window.history.back();
};
</script>

<template>

    <Head title="Editar Reseña" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Reseña: <span class="text-blue-600 font-medium">{{ reseña?.libros?.titulo }}</span>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <form @submit.prevent="actualizar" class="space-y-8">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <Label class="text-gray-700 font-bold ml-1">Inicio de la lectura</Label>
                                    <Input type="date" v-model="form.inicio_lectura"
                                        class="w-full border-gray-200 focus:ring-blue-500 rounded-xl" />
                                </div>

                                <div class="space-y-2">
                                    <Label class="text-gray-700 font-bold ml-1">Fin de la lectura</Label>
                                    <Input type="date" v-model="form.final_lectura"
                                        class="w-full border-gray-200 focus:ring-blue-500 rounded-xl" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <Label class="text-gray-700 font-bold ml-1">Tiempo (días)</Label>
                                    <Input type="number" v-model="form.tiempo_lectura"
                                        class="w-full border-gray-200 rounded-xl" />
                                </div>

                                <div class="space-y-2">
                                    <Label class="text-gray-700 font-bold ml-1">Estado</Label>
                                    <select v-model="form.estado"
                                        class="w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm">
                                        <option value="Leyendo">Leyendo</option>
                                        <option value="Terminado">Terminado</option>
                                        <option value="Abandonado">Abandonado</option>
                                        <option value="Pendiente">Pendiente</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <Label class="text-gray-700 font-bold ml-1">Puntuación (0-5)</Label>
                                    <Input type="number" min="0" max="5" v-model="form.puntuacion"
                                        class="w-full border-gray-200 rounded-xl" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label class="text-gray-700 font-bold ml-1">Tu Reseña Personal</Label>
                                <textarea v-model="form.reseña" rows="5"
                                    class="w-full border-gray-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl shadow-sm transition-all"
                                    placeholder="Escribe aquí tus impresiones sobre el libro..."></textarea>
                            </div>

                            <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                                <div class="max-w-7xl py-6 px-4 sm:px-6 lg:px-8 flex">
                                    <button @click="volverAtras"
                                        class="inline-flex items-center px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-green-100 transition-all active:scale-95 disabled:opacity-50">
                                        Cancelar
                                    </button>
                                </div>
                                <button type="submit" :disabled="form.processing"
                                    class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-xl font-bold text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-200 transition-all disabled:opacity-50">
                                    {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>