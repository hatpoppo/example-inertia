<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    posts: {
        type: Object,
    },
});
</script>

<template>
    <Head title="Posts" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Posts
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <div
                        v-for="post in posts"
                        :key="post.id"
                        class="flex items-center shadow-md bg-white rounded-xl p-4 mb-4"
                    >
                        <div
                            class="flex flex-col text-left justify-between pl-3 space-y-5"
                        >
                            <h3
                                class="text-xl font-semibold text-indigo-600 focus:text-indigo-800"
                            >
                                <Link :href="route('posts.show', post.id)">{{
                                    post.title
                                }}</Link>
                            </h3>
                            <p>{{ post.excerpt }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
