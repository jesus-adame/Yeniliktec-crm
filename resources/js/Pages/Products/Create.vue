<template>
    <app-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight container mx-auto">Productos</h1>
        </template>
        <div class="container-fluid mx-auto mt-5">
            <div class="w-full mx-auto max-w-md">
                <form class="bg-white shadow-md px-6 pt-6 pb-8 mb-5" @submit="sendForm">
                    <h2 class="font-bold mb-4">Nuevo producto</h2>
                    <div class="flex flex-wrap">
                        <div class="w-1/2 px-2">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                    Nombre
                                </label>
                                <input class="shadow appearance-none border w-full
                                py-2 px-3 text-gray-700 leading-tight focus:outline-none" id="name"
                                type="text" name="name" placeholder="Nombre">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                    Descripción
                                </label>
                                <input class="shadow appearance-none border w-full
                                py-2 px-3 text-gray-700 leading-tight focus:outline-none" id="description"
                                name="description"
                                type="text" placeholder="description">
                                <!-- <p class="text-red-500 text-xs italic">Please choose a password.</p> -->
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                                    Precio
                                </label>
                                <input class="shadow appearance-none border w-full
                                py-2 px-3 text-gray-700 leading-tight focus:outline-none" id="price"
                                type="number" name="price" placeholder="Precio">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="unity">
                                    Unidad
                                </label>
                                <select
                                    class="shadow appearance-none border w-full
                                    py-2 px-3 text-gray-700 leading-tight
                                    capitalize focus:outline-none"
                                    name="unity"
                                    id="unity">
                                    <option value="">- Elegir -</option>
                                    <option v-for="unity in unities" :key="unity.id" :value="unity.name">{{ unity.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-1/2 px-2">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="tax_amount">
                                    Porcentaje de impuesto
                                </label>
                                <input class="shadow appearance-none border w-full
                                py-2 px-3 text-gray-700 leading-tight focus:outline-none" id="tax_amount"
                                type="number" name="tax_amount" placeholder="Impuesto">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                                    Estatus
                                </label>
                                <select class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                    name="status" id="status">
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="slug">
                                    Slug
                                </label>
                                <input class="shadow appearance-none border w-full
                                py-2 px-3 text-gray-700 leading-tight focus:outline-none" id="slug"
                                type="text" name="slug" placeholder="Slug">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="manage_stock">
                                    Maneja stock
                                </label>
                                <select class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                    name="manage_stock" id="manage_stock">
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 focus:outline-none" type="submit">
                                Registrar
                            </button>
                            <inertia-link :href="route('products.index')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-4 focus:outline-none">
                                Cancelar
                            </inertia-link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

export default {
    inheritAttrs: false,

    components: {
        AppLayout,
    },

    props: ['unities'],

    setup() {
        const sendForm = async (e) => {
            let form = new FormData(e.target);
            e.preventDefault();

            try {
                let response = await axios.post(route('products.store'), form);

                __alert_notification(response.data.message);
                Inertia.visit(route('products.index'));
                
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: error.response.data.message
                })
            }
        }

        return { sendForm };
    }
}
</script>