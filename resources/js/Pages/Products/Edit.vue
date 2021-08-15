<template>
    <app-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Productos</h1>
        </template>
        <div class="container-fluid mx-auto mt-5">
            <div class="w-full mx-auto max-w-xs">
                <form class="bg-white shadow-md px-8 pt-6 pb-8 mb-4" @submit="sendForm">
                    <input type="hidden" name="_method" value="put">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Nombre
                        </label>
                        <input class="shadow appearance-none border w-full
                        py-2 px-3 text-gray-700 leading-tight focus:outline-none" id="name"
                        v-model="form.name"
                        type="text" name="name" placeholder="Nombre">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                            Descripción
                        </label>
                        <input class="shadow appearance-none border w-full
                        py-2 px-3 text-gray-700 leading-tight focus:outline-none" id="description"
                        v-model="form.description"
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
                            v-model="form.price"
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
                            v-model="form.unity"
                            id="unity">
                            <option value="">- Elegir -</option>
                            <option v-for="unity in unities" :key="unity.id" :value="unity.name">{{ unity.name }}</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                            Estatus
                        </label>
                        <select class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                            v-model="form.status"
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
                            v-model="form.slug"
                            type="text" name="slug" placeholder="Slug">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="manage_stock">
                            Maneja stock
                        </label>
                        <select class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                            v-model="form.manage_stock"
                            name="manage_stock" id="manage_stock">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-between">
                        <button class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-4 focus:outline-none" type="submit">
                            Actualizar
                        </button>
                        <a :href="route('products.index')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-4 focus:outline-none">
                            Cancelar
                        </a>
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
import { reactive, toRefs } from '@vue/reactivity';

export default {
    inheritAttrs: false,

    components: {
        AppLayout,
    },

    props: ['product', 'unities'],

    setup(props) {
        let { product } = toRefs(props);

        let form = reactive(JSON.parse(JSON.stringify(product.value)));

        const sendForm = async (e) => {
            let formData = new FormData(e.target);
            e.preventDefault();

            try {
                let response = await axios.post(route('products.update', { id: product.value.id }), formData);

                __alert_notification(response.data.message)
                .then(() => Inertia.visit(route('products.index')));
                
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: error.response.data.message
                })
            }
        }

        return {
            sendForm,
            form,
        };
    }
}
</script>