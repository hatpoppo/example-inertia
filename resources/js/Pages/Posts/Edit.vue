<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { toTypedSchema } from "@vee-validate/zod";
import { useForm as useValidateForm } from "vee-validate";
import * as z from "zod";
const props = defineProps({
    post: {
        type: Object,
    },
});

const form = useForm({
    title: "",
    excerpt: "",
    body: "",
});

const formSchema = toTypedSchema(
    z.object({
        title: z.string().min(1, { message: "必須です" }),
        excerpt: z.string().min(1, { message: "必須です" }).max(160, {
            message: "１６０文字以内です",
        }),
        body: z.string().min(1, { message: "必須です" }),
    })
);

const { handleSubmit } = useValidateForm({
    validationSchema: formSchema,
    initialValues: {
        title: props.post ? props.post.title : "",
        excerpt: props.post ? props.post.excerpt : "",
        body: props.post ? props.post.body : "",
    },
});
const onSubmit = handleSubmit((values) => {
    Object.assign(form, values);
    props.post
        ? form.patch(route("posts.update", props.post))
        : form.post(route("posts.store"));
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
                        <form @submit="onSubmit" class="mt-6 space-y-6">
                            <FormField v-slot="{ componentField }" name="title">
                                <FormItem>
                                    <FormLabel>タイトル</FormLabel>
                                    <FormControl>
                                        <Input
                                            type="text"
                                            placeholder="タイトル"
                                            v-bind="componentField"
                                        />
                                    </FormControl>
                                    <FormDescription>
                                        タイトルを入力してください
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                            <FormField
                                v-slot="{ componentField }"
                                name="excerpt"
                            >
                                <FormItem>
                                    <FormLabel>抜粋</FormLabel>
                                    <FormControl>
                                        <Textarea
                                            placeholder="抜粋"
                                            v-bind="componentField"
                                        />
                                    </FormControl>
                                    <FormDescription>
                                        抜粋を入力してください
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                            <FormField v-slot="{ componentField }" name="body">
                                <FormItem>
                                    <FormLabel>本文</FormLabel>
                                    <FormControl>
                                        <Textarea
                                            placeholder="本文"
                                            class="w-full"
                                            v-bind="componentField"
                                        />
                                    </FormControl>
                                    <FormDescription>
                                        本文を入力してください
                                    </FormDescription>
                                    <FormMessage />
                                </FormItem>
                            </FormField>

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
