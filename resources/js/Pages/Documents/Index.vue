<template>
    <app-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Documentos
            </h1>
        </template>

        <div class="container bg-white h-full mx-auto sm:px-6 p-8 mt-4">
            <inertia-link class="p-1 px-3 rounded mb-4 inline-block text-white bg-green-400" :href="route('documents.create')">Registrar</inertia-link>

            <div class="mx-auto card bg-white">
                <!-- This example requires Tailwind CSS v2.0+ -->
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class=" py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class=" shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nombre
                                            </th>
                                            <th scope="col"
                                                class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ruta
                                            </th>
                                            <th
                                                scope="col"
                                                class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Estatus
                                            </th>
                                            <th
                                                scope="col"
                                                class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Relacionados
                                            </th>
                                            <th
                                                scope="col"
                                                class="relative px-6 py-3">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="document in documents.data" :key="document.id">
                                            <td class=" px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="font-bold text-sm text-gray-900">
                                                            {{ document.name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class=" px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ document.path }}
                                                </div>
                                            </td>
                                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                                <span class=" px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ document.mime_type }}
                                                </span>
                                            </td>
                                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                                <span v-for="lead in document.leads" :key="lead.id"
                                                    class=" px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ lead.title }}
                                                </span>
                                                <span class=" px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ document.contact.name }}
                                                </span>
                                            </td>
                                            <td class=" px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a :href="route('documents.download', { id: document.id })" target="_blank"
                                                    class="rounded text-white mx-1 bg-blue-400 py-1 px-2 text-indigo-600hover:text-indigo-900">
                                                    Descargar
                                                </a>
                                                <button class="rounded text-white mx-1 bg-red-400 py-1 px-2 text-indigo-600hover:text-indigo-900"
                                                    @click="destroy(document.id)">Eliminar</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <Paginator class="my-6" :paginator="documents" />
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import numeral from 'numeral';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';

import Paginator from "@/components/Paginator";

export default {
    inheritAttrs: false,

    components: {
        AppLayout,
        Paginator,
    },

    props: ['documents'],

    setup() {
        const formatNumber = (number) => {
            return numeral(number).format();
        }

        const destroy = async (productId) => {
            try {
                let response = await axios.delete(route('documents.destroy', { id: productId }));

                __alert_notification(response.data.message);

                Inertia.reload({ only: ['documents'] })

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: error.response.data.message
                });
            }
        }

        return {
            formatNumber,
            Paginator,
        }
    }
};
</script>
