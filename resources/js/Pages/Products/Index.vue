<template>
    <app-layout>
        <template #header>
            <h1 class="container mx-auto font-semibold text-xl text-gray-800 leading-tight">
                Productos
            </h1>
        </template>

        <div class="container bg-white h-full mx-auto sm:px-6 p-8 mt-4">
            <inertia-link class="p-1 px-3 rounded mb-4 inline-block text-white bg-green-400" :href="route('products.create')">Registrar</inertia-link>

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
                                                Precio
                                            </th>
                                            <th
                                                scope="col"
                                                class=" px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Impuesto (IVA)
                                            </th>
                                            <th
                                                scope="col"
                                                class="relative px-6 py-3">
                                                <span class="sr-only">Editar</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="product in products.data" :key="product.id">
                                            <td class=" px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="font-bold text-sm font-medium text-gray-900">
                                                            {{ product.name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class=" px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ product.description }}
                                                </div>
                                            </td>
                                            <td class="capitalize px-6 py-4 whitespace-nowrap">
                                                <span class=" px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ product.status }}
                                                </span>
                                            </td>
                                            <td class=" px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                $ {{ formatNumber(product.price) }}
                                            </td>
                                            <td class=" px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500">
                                                {{ product.tax_amount }} %
                                            </td>
                                            <td class=" px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <inertia-link :href="route('products.edit', { id: product.id })"
                                                    class="rounded text-white mx-1 bg-yellow-400 py-1 px-2 text-indigo-600hover:text-indigo-900">
                                                    Editar
                                                </inertia-link>
                                                <button class="rounded text-white mx-1 bg-red-400 py-1 px-2 text-indigo-600hover:text-indigo-900"
                                                    @click="destroy(product.id)">Eliminar</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>                                                                
                            </div>

                            
                        </div>
                    </div>
                </div>
                <Paginator class="my-6" :paginator="products" />
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import numeral from 'numeral';
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';
import Paginator from '@/components/Paginator';

export default {
    inheritAttrs: true,

    components: {
        AppLayout,
        Paginator,
    },

    props: ['products'],

    setup() {
        const formatNumber = (number) => {
            return numeral(number).format();
        }

        const destroy = async (productId) => {
            try {
                let response = await axios.delete(route('products.destroy', { id: productId }));

                __alert_notification(response.data.message)
                .then(() => Inertia.reload({ only: ['products'] }));

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: error.response.data.message
                })
            }
        }

        return {
            formatNumber,
            destroy,
        }
    }
};
</script>
