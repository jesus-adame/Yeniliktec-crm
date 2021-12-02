<template>
    <app-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Cotizaciones
            </h1>
        </template>

        <div class="container bg-white h-full mx-auto sm:px-6 p-8 mt-4">
            <inertia-link class="p-1 px-3 rounded mb-4 inline-block text-white bg-green-400" :href="route('quotes.create')">Registrar</inertia-link>

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
                                                Contacto
                                            </th>
                                            <th scope="col"
                                                class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Descripción
                                            </th>
                                            <th
                                                scope="col"
                                                class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Estatus
                                            </th>
                                            <th
                                                scope="col"
                                                class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Valor
                                            </th>
                                            <th
                                                scope="col"
                                                class="relative px-6 py-3">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="quote in quotes.data" :key="quote.id">
                                            <td class=" px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="font-bold text-sm text-gray-900">
                                                            <a :href="route('print.quote', { quote: quote.id })" target="_blank">{{ quote.contact.name }}</a>
                                                        </div>
                                                        <div
                                                            class="text-sm text-gray-500">
                                                            {{ quote.contact.email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class=" px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    <table>
                                                        <tr v-for="item in quote.items" :key="item.id">
                                                            <td>
                                                                <span class="px-2 text-green-800 bg-green-100 rounded-full">
                                                                    {{ item.quantity }}
                                                                    {{ item.product.unity }}
                                                                </span>
                                                                * {{ item.product.sku }} {{ item.product.name }}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                                <span class=" px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ quote.status }}
                                                </span>
                                            </td>
                                            <td class=" px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                $ {{ formatNumber(quote.total) }}
                                            </td>
                                            <td class=" px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a class="rounded text-white mx-1 bg-gray-500 py-1 px-2 hover:text-indigo-900" :href="route('print.quote', { quote: quote.id })" target="_blank">
                                                    Imprimir
                                                </a>
                                                <inertia-link :href="route('quotes.edit', { id: quote.id })"
                                                    class="rounded text-white mx-1 bg-yellow-400 py-1 px-2 text-indigo-600hover:text-indigo-900">
                                                    Editar
                                                </inertia-link>
                                                <button class="rounded text-white mx-1 bg-red-400 py-1 px-2 text-indigo-600hover:text-indigo-900"
                                                    @click="clickDelete(quote.id)">Eliminar</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <Paginator class="my-6" :paginator="quotes" />
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import numeral from 'numeral';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';

import Paginator from"@/components/Paginator";

export default {
    inheritAttrs: false,

    components: {
        AppLayout,
        Paginator,
    },

    props: ['quotes'],

    setup() {
        const formatNumber = (number) => {
            return numeral(number).format();
        }

        const clickDelete = (productId) => {
            __confirm_alert()
                .then(result => {
                    if (result.isConfirmed) {
                        destroy(productId);
                    }
                })
        }

        const destroy = async (productId) => {
            try {
                let response = await axios.delete(route('quotes.destroy', { id: productId }));

                __alert_notification(response.data.message);

                Inertia.reload({ only: ['quotes'] })

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: error.response.data.message
                });
            }
        }

        return {
            formatNumber,
            destroy,
            clickDelete,
        }
    }
};
</script>
