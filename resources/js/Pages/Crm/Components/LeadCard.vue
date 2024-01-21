<template>
    <div class="px-4 py-2 shadow bg-white transform hover:scale-95 rounded cursor-pointer transition">
        <div class="flex justify-between items-center py-1">
            <h2 class="uppercase font-bold text-sm">{{ lead.title }}</h2>
            <span class="text-xs py-1 px-2 rounded-full inline-block text-center text-white"
                :style="{ background: statusColors[lead.status]}">
                {{ lead.status }}
            </span>
        </div>
        <div class="text-xs mt-1">
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
