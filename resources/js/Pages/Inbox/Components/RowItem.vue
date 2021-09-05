<template>
    <tr>
        <td class="border border-gray-200 py-2 px-4">
            <div class="cursor-pointer">
                <h3 class="font-bold">{{ message.from }}</h3>
                <h4>{{ message.subject }}</h4>
                <span class="text-gray-500 block">{{ fromFormat(message.date) }}</span>
            </div>
        </td>
        <td class="border border-gray-200 p-2">
            <div class="text-center text-gray-500 text-sm">
                <span class="block">{{ dateFormat(message.date) }}</span>
                <span class="block">{{ timeFormat(message.date) }}</span>
            </div>
        </td>
    </tr>
</template>

<script>
import moment from 'moment'
export default {
    props: ['message'],

    methods: {
        fromFormat(date) {
            return moment(date).fromNow()
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