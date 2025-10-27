<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    post: {
        type: Object,
    },
});
</script>

<template>
    <Head title="Post Show" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <h2
                    class="flex-1 text-xl font-semibold leading-tight text-gray-800"
                >
                    Post Show
                </h2>
                <Link
                    :href="route('posts.edit', post.id)"
                    class="shadow-md bg-white rounded-xl px-4 mx-4"
                    >編集</Link
                >
                <Link
                    :href="route('posts.index')"
                    class="shadow-md bg-white rounded-xl px-4 mx-4"
                    >戻る</Link
                >
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <div class="flex flex-col">
                        <h2 class="text-2xl underline font-semibold bg-white">
                            タイトル
                        </h2>
                        <h3
                            class="text-xl font-semibold shadow-md bg-white rounded-xl p-4 mb-4"
                        >
                            {{ post.title }}
                        </h3>
                        <h2 class="text-2xl underline font-semibold bg-white">
                            抜粋
                        </h2>
                        <p class="shadow-md bg-white rounded-xl p-4 mb-4">
                            {{ post.excerpt }}
                        </p>
                        <h2 class="text-2xl underline font-semibold bg-white">
                            本文
                        </h2>
                        <p class="shadow-md bg-white rounded-xl p-4 mb-4">
                            {{ post.body }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
