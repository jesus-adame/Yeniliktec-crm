<template>
    <app-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight container mx-auto">Cotizaciones</h1>
        </template>
        <div class="container-fluid mx-auto my-5">
            <div class="w-full mx-auto container">
                <form class="bg-white flex flex-wrap shadow-md px-8 pt-6 pb-8 mb-4" @submit="sendForm">
                    <input type="hidden" name="_method" value="put">
                    <div class="md:w-1/2 md:px-2">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="lead">
                                Lead ID
                            </label>
                            <select class="shadow appearance-none border w-full
                                py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                v-model="form.lead_id"
                                name="lead" id="lead">
                                <option value="">- Elegir -</option>
                                <option v-for="lead in leads" :key="lead.id" :value="lead.id">{{ lead.title }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="md:w-1/2 md:px-2">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                                Estatus
                            </label>
                            <select class="shadow appearance-none border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                v-model="form.status"
                                name="status" id="status">
                                <option value="">- Elegir -</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="block py-5 w-full">
                        <hr>
                    </div>
                    <div>
                        <select class="shadow appearance-none border w-full
                            py-2 px-3 text-gray-700 leading-tight focus:outline-none mb-4" name="products" id="products" v-model="selectedItem">
                            <option value="">- Elegir -</option>
                            <option v-for="product in products" :key="product.id" :value="product">{{ product.name }}</option>
                        </select>
                        <button type="button"
                            class="inline-flex mb-4 items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900"
                            @click="addItem">Agregar</button>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200 mb-5">
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
                                    class=" px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio
                                </th>
                                <th
                                    scope="col"
                                    class=" px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Impuesto
                                </th>
                                <th
                                    scope="col"
                                    class=" px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cantidad
                                </th>
                                <th
                                    scope="col"
                                    class=" px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal
                                </th>
                                <th
                                    scope="col"
                                    class="relative px-6 py-3">
                                    <span class="sr-only">Editar</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(item, index) in items" :key="item.id">
                                <td class=" px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class=" text-sm font-bold text-gray-900">
                                                {{ item.name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class=" px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ item.description }}
                                    </div>
                                </td>
                                <td class=" px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                                    $ {{ formatNumber(item.price) }} <span class="pr-2 capitalize">({{ item.unity }})</span>
                                </td>
                                <td class=" px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                                    {{ item.tax_amount }} % <span class="pr-2 capitalize">(IVA)</span>
                                </td>
                                <td class="flex justify-center items-center px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                                    <button type="button" class="cursor-pointer" @click="reduceQtyItem(index)">
                                        <svg class="fill-current text-gray-600 w-3" viewBox="0 0 448 512">
                                            <path d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"/>
                                        </svg>
                                    </button>
                                    <input class="mx-2 border p-1 text-center w-14" v-model="item.quantity" @keyup="item.subtotal = calcSubtotal(item)">
                                    <button type="button" class="cursor-pointer" @click="increaseQtyItem(index)">
                                        <svg class="fill-current text-gray-600 w-3" viewBox="0 0 448 512">
                                            <path d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"/>
                                        </svg>
                                    </button>
                                </td>
                                <td class=" px-6 py-4 text-right whitespace-nowrap text-sm text-gray-500">
                                    $ {{ formatNumber(item.subtotal) }}
                                </td>
                                <td class=" px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button
                                        type="button"
                                        class="rounded text-white mx-1 bg-red-400 py-1 px-2 text-indigo-600hover:text-indigo-900"
                                        @click="removeItem(index)">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td class="text-right" colspan="5">Impuesto</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium border-b-2 border-yellow-800">
                                    <span class="block">$ {{ formatNumber(totalTax) }}</span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="text-right" colspan="5">Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium border-b-2 border-green-800">
                                    <span class="block">$ {{ formatNumber(total) }}</span>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="flex items-center justify-end w-full">
                        <button class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-4 focus:outline-none mr-4" type="submit">
                            Actualizar
                        </button>
                        <inertia-link :href="route('quotes.index')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-4 focus:outline-none">
                            Cancelar
                        </inertia-link>
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
import { reactive, ref, toRefs } from '@vue/reactivity';
import { computed } from '@vue/runtime-core';

export default {
    inheritAttrs: false,

    components: {
        AppLayout,
    },

    props: ['products', 'quote', 'leads'],

    setup(props) {
        let items = ref([]);
        let selectedItem = ref('');
        let { quote } = toRefs(props);

        let form = reactive(JSON.parse(JSON.stringify(quote.value)));

        form.items.forEach(item => {
            item.subtotal = parseFloat(item.price) * item.quantity;
            items.value.push(item);
        });

        const formatNumber = (number) => {
            return numeral(number).format();
        }

        const totalTax = computed(() => {
            let countTaxes = 0;

            items.value.forEach(item => {
                countTaxes += (item.tax_amount / 100) * item.price * item.quantity;
            });

            return countTaxes;
        });

        const total = computed(() => {
            let countTotals = 0;

            items.value.forEach(item => {
                countTotals += item.subtotal + ((item.tax_amount / 100) * item.price * item.quantity);
            });

            return countTotals;
        });

        const addItem = () => {
            let itemExists = items.value.find(item => item.product_id == selectedItem.value.id);

            if (selectedItem.value == '') {
                return;
            }

            if (typeof itemExists != 'undefined') {
                itemExists.quantity++;
                itemExists.subtotal = calcSubtotal(itemExists);
                return;
            }

            selectedItem.value.product_id = selectedItem.value.id;
            selectedItem.value.quantity = 1;
            selectedItem.value.subtotal = calcSubtotal(selectedItem.value);

            items.value.push(selectedItem.value);
        }

        const removeItem = (index) => {
            items.value.splice(index, 1);
        }

        const increaseQtyItem = (index) => {
            items.value[index].quantity++;
            items.value[index].subtotal = calcSubtotal(items.value[index]);
        }

        const reduceQtyItem = (index) => {
            if (items.value[index].quantity <= 1) {
                return;
            }

            items.value[index].quantity--;
            items.value[index].subtotal = calcSubtotal(items.value[index]);
        }

        const calcSubtotal = (itemJson) => {
            return parseFloat(itemJson.price) * itemJson.quantity
        }

        const sendForm = async (e) => {
            let formData = new FormData(e.target);
            
            e.preventDefault();
            formData.append('items', JSON.stringify(items.value));

            try {
                let response = await axios.post(route('quotes.update', { id: form.id }), formData);

                __alert_notification(response.data.message);
                Inertia.visit(route('quotes.index'));
                
            } catch (fail) {
                let errors = Object.values(fail.response.data.errors);
                let text = '';

                errors.forEach(error => text += `<p>${error[0]}</p>`);

                Swal.fire({
                    icon: 'error',
                    title: fail.response.data.message,
                    html: text,
                });
            }
        }

        return {
            sendForm,
            items,
            addItem,
            selectedItem,
            formatNumber,
            removeItem,
            increaseQtyItem,
            reduceQtyItem,
            calcSubtotal,
            total,
            totalTax,
            form,
        };
    }
}
</script>