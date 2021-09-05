<template>
    <dialog-modal :show="showModal" @close="closeModal" maxWidth="3xl">
        <template v-slot:title>
            <h3 class="font-bold">{{ message.from.full }}</h3>
            <h4>{{ message.subject }}</h4>
            <div class="text-gray-500 text-sm">
                <span class="mr-2">{{ dateFormat(message.date) }}</span>
                <span class="">{{ timeFormat(message.date) }}</span>
            </div>
        </template>
        <template v-slot:content>
            <div v-html="message.body"></div>
        </template>
        <template v-slot:footer>
            <SecondaryButton @click="closeModal">Cerrar</SecondaryButton>
        </template>
    </dialog-modal>
</template>

<script>
import DialogModal from '@/Jetstream/DialogModal.vue';
import SecondaryButton from '@/Jetstream/SecondaryButton';

export default {
    props: ['message', 'showModal'],

    emits: ['closeModal'],

    components: {
        DialogModal,
        SecondaryButton,
    },

    methods: {
        closeModal() {
            this.$emit('closeModal');
        },
        dateFormat(date) {
            return moment(date).format('DD-MM-YYYY')
        },
        timeFormat(date) {
            return moment(date).format('h:mm a')
        }
    }
}
</script>