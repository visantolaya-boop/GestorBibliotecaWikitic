<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import Input from '@/Components/Input.vue';
import Label from '@/Components/Label.vue'; // Usa <label class="font-bold text-gray-700"> si no tienes el componente
import { nextTick, ref } from 'vue';

const props = defineProps({
    libro: Object,
    isUpdating: Boolean,
    ubicaciones: {
        type: Array,
        default: () => ([]),
    },
    generos: {
        type: Array,
        default: () => ([]),
    }
});

const form = useForm({
    _method: props.isUpdating ? 'put' : 'post',
    titulo: props.libro.titulo || '',
    autor: props.libro.autor || '',
    anio: props.libro.anio || '',
    editorial: props.libro.editorial || '',
    paginas: props.libro.paginas || '',
    genero_id: props.libro.genero_id ?? '',
    portada: null,
    ubicacion_id: props.libro.ubicacion_id ?? '',
});

const actualizar = () => {
    if (props.isUpdating) {
        form.post(route('books.update', props.libro.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('books.store'));
    }
};

const inputNombreUbicacion = ref(null);

const nuevaUbicacion = (event) => {
    if (event.target.value === 'nueva') {
        showModal.value = true;
        nextTick(() => {
            if (inputNombreUbicacion.value) {
                inputNombreUbicacion.value.focus();
            }
        });
    }
}

const inputNombreGenero = ref(null);

const nuevoGenero = (event) => {
    if (event.target.value === 'nuevo') {
        showModal2.value = true;
        nextTick(() => {
            if (inputNombreGenero.value)
                inputNombreGenero.value.focus();
        });
    }
}

const showModal = ref(false),
    ubicacionForm = useForm({
        nombre: '',
    });


const showModal2 = ref(false),
    generoForm = useForm({
        nombre: '',
    });

const guardarUbicacion = () => {
    ubicacionForm.post(route('ubi.store'),
        {

            onSuccess: () => {
                showModal.value = false;
                ubicacionForm.reset();
            }
        })
}

const guardarGenero = () => {
    generoForm.post(route('genero.store'),
        {

            onSuccess: () => {
                showModal2.value = false;
                generoForm.reset();
            }
        })
}


const volverAtras = () => {
    window.history.back();
};


</script>

<template>

    <Head :title="isUpdating ? 'Editar Libro' : 'Nuevo Libro'" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ isUpdating ? `Editar: ${libro.titulo}` : 'Añadir Nuevo Libro' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <form @submit.prevent="actualizar" @keydown.enter.prevent class="space-y-6">

                            <div class="space-y-2">
                                <label class="block font-bold text-gray-700 ml-1">Título del Libro</label>
                                <Input type="text" v-model="form.titulo" placeholder="Ej: El Quijote"
                                    class="w-full border-gray-200 focus:ring-blue-500 rounded-xl shadow-sm" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Autor</label>
                                    <Input type="text" v-model="form.autor" placeholder="Nombre del autor.."
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                </div>
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Genero</label>
                                    <select v-model="form.genero_id"
                                        class="w-full border-gray-200  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50  rounded-xl shadow-sm"
                                        @change="nuevoGenero">
                                        <option v-for="g in generos" :key="g.id" :value="g.id">
                                            {{ g.nombre }}
                                        </option>
                                        <option value="nuevo">
                                            Añadir nuevo genero
                                        </option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Año de Publicación</label>
                                    <Input type="text" v-model="form.anio" placeholder="2024"
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Editorial</label>
                                    <Input type="text" v-model="form.editorial" placeholder="Editorial.."
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                </div>
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Nº de Páginas</label>
                                    <Input type="number" v-model="form.paginas" placeholder="0"
                                        class="w-full border-gray-200 rounded-xl shadow-sm" />
                                </div>
                                <div class="space-y-2">
                                    <label class="block font-bold text-gray-700 ml-1">Ubicación</label>
                                    <select v-model="form.ubicacion_id"
                                        class="w-full border-gray-200  focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50  rounded-xl shadow-sm"
                                        @change="nuevaUbicacion">
                                        <option v-for="u in ubicaciones" :key="u.id" :value="u.id">
                                            {{ u.nombre }}
                                        </option>
                                        <option value="nueva">
                                            Añadir nueva ubicación
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Ubicacion -->
                            <div v-if="showModal"
                                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                <div class="bg-white p-6 rounded-lg shadow-xl w-96">
                                    <h3 class="text-lg font-bold mb-4">Nueva Ubicación</h3>

                                    <input v-model="ubicacionForm.nombre" ref="inputNombreUbicacion" type="text"
                                        placeholder="Nombre de la ubicacion..." class="w-full border p-2 mb-4"
                                        @input="ubicacionForm.clearErrors('nombre')" />

                                    <p v-if="ubicacionForm.errors.nombre" class="text-red-500 text-xs mt-1 italic">
                                        {{ ubicacionForm.errors.nombre }}
                                    </p>

                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="showModal = false">Cancelar</button>
                                        <button type="button" @click="guardarUbicacion"
                                            :disabled="ubicacionForm.processing"
                                            class="bg-blue-600 text-white px-4 py-2 rounded">
                                            Guardar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Genero -->
                            <div v-if="showModal2"
                                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                <div class="bg-white p-6 rounded-lg shadow-xl w-96">
                                    <h3 class="text-lg font-bold mb-4">Nuevo Genero</h3>

                                    <input v-model="generoForm.nombre" ref="inputNombreGenero" type="text"
                                        placeholder="Nombre del genero..." class="w-full border p-2 mb-4"
                                        @input="generoForm.clearErrors('nombre')" />

                                    <p v-if="generoForm.errors.nombre" class="text-red-500 text-xs mt-1 italic">
                                        {{ generoForm.errors.nombre }}
                                    </p>

                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="showModal2 = false">Cancelar</button>
                                        <button type="button" @click="guardarGenero" :disabled="generoForm.processing"
                                            class="bg-blue-600 text-white px-4 py-2 rounded">
                                            Guardar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!---->
                            <div class="p-6 bg-blue-50/50 rounded-2xl border-2 border-dashed border-blue-100">
                                <label class="block font-bold text-blue-800 mb-2">Portada del Libro</label>
                                <input type="file" @input="form.portada = $event.target.files[0]" class="block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-full file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-blue-600 file:text-white
                                              hover:file:bg-blue-700 transition-all cursor-pointer" accept="image/*" />

                                <p v-if="form.errors.portada" class="mt-2 text-red-500 text-sm font-medium italic">
                                    {{ form.errors.portada }}
                                </p>
                            </div>


                            <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                                <div class="max-w-7xl py-6 px-4 sm:px-6 lg:px-8 flex">
                                    <button @click="volverAtras"
                                        class="inline-flex items-center px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-green-100 transition-all active:scale-95 disabled:opacity-50">
                                        Cancelar
                                    </button>
                                </div>
                                <button type="submit" :disabled="form.processing"
                                    class="inline-flex items-center px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-green-100 transition-all active:scale-95 disabled:opacity-50">
                                    {{ isUpdating ? 'Guardar Cambios' : 'Crear Libro' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>