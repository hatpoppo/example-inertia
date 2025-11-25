<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { Star } from "lucide-vue-next";
import { Button } from "@/components/ui/button";
import CommentsEdit from "@/Pages/Posts/Comments/Edit.vue";

const d = defineProps({
    post: {
        type: Object,
    },
    isFavourite: {
        type: Boolean,
    },
    comments: {
        type: Array,
    },
});
const form = useForm({
    post_id: d.post.id,
});
function destroy() {
    if (confirm("Are you sure?")) {
        return true;
    } else {
        return false;
    }
}
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
                    :href="route('posts.destroy', post.id)"
                    method="delete"
                    :onBefore="destroy"
                    class="shadow-md bg-white rounded-xl px-4 mx-4"
                    >削除</Link
                >
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
                        <form
                            class="mb-4"
                            v-if="isFavourite"
                            @submit.prevent="
                                form.delete(route('favorite.destroy', post.id))
                            "
                        >
                            <Button variant="outline" size="lg">
                                <Star fill="yellow" strokeWidth="{1}" />
                            </Button>
                        </form>
                        <form
                            class="mb-4"
                            v-else
                            @submit.prevent="
                                form.post(route('favorite.store', post.id))
                            "
                        >
                            <Button variant="secondary" size="lg">
                                <Star fill="white" strokeWidth="{1}" />
                            </Button>
                        </form>
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
        <template #sub>
            <CommentsEdit :post="post" :comments="comments" />
        </template>
    </AuthenticatedLayout>
</template>
