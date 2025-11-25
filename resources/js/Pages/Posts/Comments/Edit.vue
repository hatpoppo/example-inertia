<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import { toTypedSchema } from "@vee-validate/zod";
import { useForm as useValidateForm } from "vee-validate";
import * as z from "zod";
const props = defineProps({
    post: {
        type: Object,
    },
    comments: {
        type: Array,
    },
});

const page = usePage();
const form = useForm({
    content: "",
    post_id: props.post.id,
    user_id: page.props.auth.user.id,
});

const formSchema = toTypedSchema(
    z.object({
        content: z.string().min(1, { message: "必須です" }).max(225, {
            message: "225文字以内です",
        }),
    })
);

const { handleSubmit, resetForm } = useValidateForm({
    validationSchema: formSchema,
    initialValues: {
        content: "",
    },
});
const onSubmit = handleSubmit((values) => {
    Object.assign(form, values);
    form.post(route("posts.comments.store", props.post));
    resetForm();
});
const displayDate = (date) => {
    return new Date(date).toLocaleDateString("ja-JP", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>

<template>
    <div class="pb-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <div class="flex flex-col">
                    <form @submit="onSubmit" class="mt-6 space-y-6">
                        <FormField v-slot="{ componentField }" name="content">
                            <FormItem>
                                <FormLabel>コメント</FormLabel>
                                <FormControl>
                                    <Input
                                        type="text"
                                        placeholder="コメント"
                                        v-bind="componentField"
                                    />
                                </FormControl>
                                <FormDescription>
                                    コメントを入力してください
                                </FormDescription>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing"
                                >投稿</PrimaryButton
                            >
                        </div>
                    </form>
                    <Table>
                        <TableCaption>コメント一覧</TableCaption>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[140px]">
                                    投稿日
                                </TableHead>
                                <TableHead class="w-[400px]"> 内容 </TableHead>
                                <TableHead class="w-[140px]">
                                    投稿者
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="comment in comments"
                                :key="comment.id"
                            >
                                <TableCell>{{
                                    displayDate(comment.created_at)
                                }}</TableCell>
                                <TableCell>{{ comment.content }}</TableCell>
                                <TableCell>{{ comment.user.name }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </div>
</template>
