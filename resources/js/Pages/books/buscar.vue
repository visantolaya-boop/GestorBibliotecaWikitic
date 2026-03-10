<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, usePage } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import NavLink from "@/Components/NavLink.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import { computed, ref, watch } from "vue";
import Pagination from "@/Components/Pagination.vue";
import Swal from "sweetalert2";

const props = defineProps({
    libros: Object,
    filters: {
        type: Object,
        default: () => ({}),
    },
});

let search = ref(props.filters?.search || ""),
    pageNumber = ref(1);

let librosUrl = computed(() => {
    let url = new URL(route("books.buscar"));

    url.searchParams.append("page", pageNumber.value);

    if (search.value) {
        url.searchParams.append("search", search.value);
    }

    return url;
});

watch(
    search,
    (value) => {
        Inertia.get(
            route("books.buscar"),
            { search: value },
            {
                preserveState: true,
                replace: true,
                preserveScroll: true,
            },
        );
    },
    { deep: true },
);

const eliminarLibro = (id, titulo) => {
    Swal.fire({
        title: "Estas seguro?",
        text: `Vas a eliminar el libro "${titulo}". ¡Esta acción no se puede deshacer!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3b82f6",
        cancelButtoncolor: "#ef4444",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route("books.destroy", id), {
                onSuccess: () => {
                    Swal.fire(
                        "¡Eliminado!",
                        "El libro ha sido borrado correctamente.",
                        "success",
                    );
                },
                onError: () => {
                    Swal.fire(
                        "Error",
                        "No se pudo eliminar el libro.",
                        "error",
                    );
                },
            });
        }
    });
};
</script>

<template>

    <Head title="Busqueda" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Busqueda personalizada de libros
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!--Form con los buscadores-->
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-8">
                            <form class="flex flex-col sm:flex-row gap-4 items-center justify-between">

                                <div class="relative w-full sm:max-w-md group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                        </svg>
                                    </div>
                                    <Input v-model="search" name="buscarT" placeholder="Buscar por título, autor..."
                                        class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 rounded-xl transition-all outline-none" />
                                </div>

                                <NavLink :href="route('books.create')"
                                    class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-bold transition-all shadow-md shadow-green-100 active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Nuevo Libro
                                </NavLink>
                            </form>

                            <div class="my-5 border-t border-gray-100"></div>

                            <div class="flex justify-center sm:justify-start">
                                <Pagination :links="libros.links" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-4">
                            <div v-for="libro in libros.data" :key="libro.id"
                                class="shadow-md p-6 bg-white rounded-lg flex flex-row items-start space-x-4 border border-gray-100">
                                <div class="flex-shrink-0">
                                    <img :src="libro.portada
                                        ? '/storage/' + libro.portada
                                        : '/storage/imagenes/default_book.jpg'
                                        " :alt="libro.titulo"
                                        class="w-24 h-36 lg:w-32 lg:h-48 object-cover rounded shadow-sm" />
                                </div>

                                <div class="flex-grow flex flex-col space-y-1">
                                    <div class="text-lg font-bold text-gray-800 leading-tight">
                                        {{ libro.titulo }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <strong>Autor:</strong>
                                        {{ libro.autor }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <strong>Editorial:</strong>
                                        {{ libro.editorial }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <strong>Género:</strong>
                                        {{ libro.genero }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <strong>Año:</strong> {{ libro.anio }}
                                    </div>

                                    <div class="flex items-center space-x-1 py-1">
                                        <span class="text-sm font-bold text-gray-700 mr-1">Puntuación:</span>
                                        <div class="flex">
                                            <svg v-for="i in 5" :key="i" class="w-4 h-4" :class="i <=
                                                (libro.lecturas
                                                    ?.puntuacion || 0)
                                                ? 'text-green-400'
                                                : 'text-gray-300'
                                                " fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="text-sm text-gray-600">
                                        <strong>Estado:</strong>
                                        {{ libro.lecturas?.estado }}
                                    </div>

                                    <div class="flex flex-wrap gap-2 pt-3">
                                        <NavLink :href="route(
                                            'books.reseña',
                                            libro.lecturas?.id || 0,
                                        )
                                            " class="text-sm text-blue-600">Ver Reseña</NavLink>
                                        <NavLink :href="route('books.editar', libro.id)
                                            " class="bg-blue-500 text-white px-3 py-1 rounded text-xs font-bold">Editar
                                        </NavLink>
                                        <button @click="
                                            eliminarLibro(
                                                libro.id,
                                                libro.titulo,
                                            )
                                            "
                                            class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-bold transition-colors">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Pagination :links="libros.links" />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
