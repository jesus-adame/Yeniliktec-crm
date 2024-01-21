<template>
    <div class="border border-gray-500 p-2 w-full">
        <div class="flex justify-between items-center">
            <p class="font-bold">Contacto</p>
        </div>

        <label for="title">Nombre*</label>
        <Input name="name" v-model="form.name"/>

        <label for="title">Apellidos*</label>
        <Input name="last_name" v-model="form.last_name"/>

        <label for="title">Teléfono*</label>
        <div class="flex items-center space-x-2">
            <Input style="width: 60% !important;" name="phone_number" v-model="form.phone_number"/>
            <div class="flex flex-wrap justify-between flex-auto">
                <a class="bg-blue-500 text-white rounded flex justify-around items-center py-1 px-2 mx-auto uppercase text-xs font-bold"
                :href="'tel:' + form.phone_number">
                    <figure class="w-3 mr-1">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="phone" class="fa-phone w-full" role="img" viewBox="0 0 512 512">
                            <path fill="currentColor" d="M493.4 24.6l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-36 76.7-98.9 140.5-177.2 177.2l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48C3.9 366.5-2 378.1.6 389.4l24 104C27.1 504.2 36.7 512 48 512c256.1 0 464-207.5 464-464 0-11.2-7.7-20.9-18.6-23.4z"/>
                        </svg>
                    </figure>
                    Llamar
                </a>
                <a class="bg-green-500 text-white rounded flex justify-around items-center py-1 px-2 mx-auto uppercase text-xs font-bold"
                    :href="'https://web.whatsapp.com/send/?phone=' + form.phone_number"
                    target="_blank">
                    <figure class="w-4 mr-1">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="whatsapp" class="fa-whatsapp w-full" role="img" viewBox="0 0 448 512">
                            <path fill="currentColor" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                        </svg>
                    </figure>
                    Contactar
                </a>
            </div>
        </div>

        <label for="title">E-mail*</label>
        <Input name="email" v-model="form.email"/>

        <label for="title">Descripción</label>
        <textarea class="w-full" name="description" rows="3" v-model="form.description"></textarea>

        <Button @click="updateContact">Actualizar</Button>
    </div>
</template>

<script>
import Input from '@/Jetstream/Input';
import { reactive, toRefs } from '@vue/reactivity';
import Button from '@/Jetstream/Button';
import SecondaryButton from '@/Jetstream/SecondaryButton';
import { onMounted } from '@vue/runtime-core';
import axios from 'axios';

export default {
    components: {
        Input,
        Button,
        SecondaryButton,
    },

    props: ['contact', 'lead'],
    
    setup(props) {
        const {contact, lead} = toRefs(props);
        const form = reactive({
            name: '',
            last_name: '',
            phone_number: '',
            email: '',
            description: '',
        });

        const updateContact = async () => {
            try {
                let response = await axios.post('/leads-contact-create-update/' + lead.value.id, form);
                __alert_notification(response.data.message);

            } catch (fail) {
                __alert_axios_errors(fail.response);
            }
        }

        onMounted(() => {
            form.name = contact.value.name;
            form.last_name = contact.value.last_name;
            form.phone_number = contact.value.phone_number;
            form.email = contact.value.email;
            form.description = contact.value.description;
        });

        return {
            form,
            updateContact,
        }
    }
}
</script>