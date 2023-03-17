<template>
    <div>
        <Header />
        <div
            class="flex items-center justify-center h-screen bg-slate-900 text-gray-50"
        >
            <h1 class="text-5xl">
                {{ message[lang].hello }}
            </h1>
        </div>
    </div>
</template>

<script setup>
import Header from "@/Components/Header.vue";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const lang = computed(() => usePage().props.lang);
const message = {
    en: {
        hello: "Hello",
    },
    th: {
        hello: "สวัสดี",
    },
};
</script>
