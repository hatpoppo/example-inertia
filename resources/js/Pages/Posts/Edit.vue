<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    post: {
        type: Object,
    },
});

const form = useForm({
    title: props.post ? props.post.title : "",
    excerpt: props.post ? props.post.excerpt : "",
    body: props.post ? props.post.body : "",
});
</script>

<template>
    <Head title="Post Edit" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <h2
                    class="flex-1 text-xl font-semibold leading-tight text-gray-800"
                >
                    Post Edit
                </h2>

                <Link
                    :href="
                        post
                            ? route('posts.show', post.id)
                            : route('posts.index')
                    "
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
                            @submit.prevent="
                                post
                                    ? form.patch(route('posts.update', post))
                                    : form.post(route('posts.store'))
                            "
                            class="mt-6 space-y-6"
                        >
                            <div>
                                <InputLabel for="title" value="title" />

                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                    autofocus
                                    autocomplete="title"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.title"
                                />
                            </div>

                            <div>
                                <InputLabel for="excerpt" value="excerpt" />

                                <TextArea
                                    id="excerpt"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.excerpt"
                                    required
                                    autocomplete="username"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.excerpt"
                                />
                            </div>
                            <div>
                                <InputLabel for="body" value="body" />

                                <TextArea
                                    id="body"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.body"
                                    required
                                    autocomplete="username"
                                    rows="8"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.body"
                                />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing"
                                    >Save</PrimaryButton
                                >
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
