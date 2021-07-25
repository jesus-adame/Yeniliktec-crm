<template>
    <div class="px-4 py-2 shadow bg-white rounded cursor-pointer">
        <div class="flex justify-between items-center pt-1">
            <h2 class="uppercase font-bold">{{ lead.title }}</h2>
            <span class="p-1 rounded-full inline-block w-40 text-center text-white" :style="{ background: statusColors[lead.status]}">{{ lead.status }}</span>
        </div>
        <div>
            {{ lead.description }}
        </div>
        <small class="text-gray-400">{{ dateFormat(lead.created_at) }}</small>
    </div>
</template>

<script>
import { toRefs } from '@vue/reactivity';
export default {
    props: ['lead'],

    setup(props) {
        const { lead } = toRefs(props);
        const dateFormat = date => {
            return moment(date).format('DD-MM-YYYY');
        }

        const statusColors = {
            'pending': '#A70000',
            'in-process': '#0E79A1',
            'finished': '#0EA150',
        };

        return {
            lead,
            statusColors,
            dateFormat,
        }
    },
}
</script>
