<script setup>
import { Link } from '@inertiajs/inertia-vue3';
defineProps({
    links: {
        type: Array,
        required: true
    }
})
</script>

<template>
    <div class="mt-6 flex gap-2">
      <Link
        v-for="(link, index) in links"
        :key="index"
        :href="link.url"
        v-html="link.label"
        class="px-3 py-1 border rounded"
        :class="{ 'bg-gray-300': link.active, 'text-gray-400': !link.url }"
      /></div>
</template>