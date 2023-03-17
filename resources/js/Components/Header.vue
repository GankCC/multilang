<template>
    <div class="flex justify-between w-full px-10 py-5 shadow-sm">
        <div>
            <h1>Company Logo</h1>
        </div>
        <ul class="flex gap-8">
            <li v-for="item in menu" key="title">
                <Link :href="item.url">{{ item[lang].title }}</Link>
            </li>
            <ul class="flex gap-1">
                <li :class="{ underline: lang == 'en' }">
                    <Link href="/language/en">EN</Link>
                </li>
                |
                <li :class="{ underline: lang == 'th' }">
                    <Link href="/language/th">TH</Link>
                </li>
            </ul>
        </ul>
    </div>
</template>
<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const menu = [
    { url: "/", en: { title: "Home" }, th: { title: "หน้าแรก" } },
    { url: "/about", en: { title: "About" }, th: { title: "เกี่ยวกับเรา" } },
    { url: "/contact", en: { title: "Contact" }, th: { title: "ติดต่อเรา" } },
];

const lang = computed(() => usePage().props.lang);
</script>
