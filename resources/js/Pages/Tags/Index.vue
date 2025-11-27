<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
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
import { Button } from "@/components/ui/button";
import { computed, ref, watch } from "vue";
import { Trash2, Pencil } from "lucide-vue-next";

const props = defineProps({
    tags: {
        type: Array,
    },
});
const tagId = ref(null);
const page = usePage();
const form = useForm({
    name: "",
    user_id: page.props.auth.user.id,
});

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(1, { message: "必須です" }).max(225, {
            message: "225文字以内です",
        }),
    })
);

const { handleSubmit, setFieldValue, resetForm } = useValidateForm({
    validationSchema: formSchema,
    initialValues: {
        name: "",
    },
});
const onSubmit = handleSubmit((values) => {
    Object.assign(form, values);
    tagId.value
        ? form.patch(
              route(
                  "tags.update",
                  props.tags.find((item) => (item.id = tagId.value))
              )
          )
        : form.post(route("tags.store"));
    resetForm();
    tagId.value = null;
});
const onDestroy = (tagId) => {
    if (confirm("Are you sure?")) {
        form.delete(route("tags.destroy", tagId));
        return true;
    } else {
        return false;
    }
};
</script>

<template>
    <Head title="Tags" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <h2
                    class="flex-1 text-xl font-semibold leading-tight text-gray-800"
                >
                    Tags
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <form @submit="onSubmit" class="mt-6 space-y-6">
                        <FormField v-slot="{ componentField }" name="name">
                            <FormItem>
                                <FormLabel>タグ</FormLabel>
                                <FormControl>
                                    <Input
                                        type="text"
                                        placeholder="タグ"
                                        v-bind="componentField"
                                    />
                                </FormControl>
                                <FormDescription>
                                    タグを入力してください
                                </FormDescription>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <div class="flex items-center gap-4">
                            <Button :disabled="form.processing">
                                <template v-if="tagId">更新</template>
                                <template v-else>登録</template>
                            </Button>
                            <Button
                                :disabled="form.processing"
                                type="button"
                                @click="
                                    resetForm();
                                    tagId = null;
                                "
                                >クリア</Button
                            >
                        </div>
                    </form>
                    <template v-for="tag in tags" :kye="tag.id">
                        <div
                            class="flex items-center bg-white p-4 m-2 shadow sm:rounded-lg sm:p-8"
                        >
                            <div class="flex-1">{{ tag.name }}</div>
                            <Button
                                @click="
                                    setFieldValue('name', tag.name);
                                    tagId = tag.id;
                                "
                            >
                                <Pencil />
                            </Button>
                            <Button @click="onDestroy(tag.id)">
                                <Trash2 />
                            </Button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
