<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from "@/components/ui/pagination";
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import { Check, ChevronsUpDown, Search, Star } from "lucide-vue-next";
import {
    Combobox,
    ComboboxAnchor,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxList,
    ComboboxTrigger,
} from "@/components/ui/combobox";
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";
import { computed, ref, watch } from "vue";
const d = defineProps({
    posts: {
        type: Object,
    },
    users: {
        type: Object,
    },
    selected_user_id: {
        type: String,
    },
});
const page = usePage();
const selectedUser = ref({});
const usersList = d.users.map((user) => ({ value: user.id, label: user.name }));
usersList.unshift({ value: "", label: "Not Selected" });
const selectedUserValue = usersList.find(
    (user) => user.value == d.selected_user_id
);
if (selectedUserValue) selectedUser.value = selectedUserValue;
const form = useForm({
    user_id: "",
});
const paginationLink = computed(() => (item) => {
    let url = d.posts.links.find((element) => element.page === item.value).url;
    if (selectedUserValue) url += "&user_id=" + selectedUserValue.value;
    return url;
});
const nextPreviousLink = computed(() => (url) => {
    if (!url) return undefined;
    if (selectedUserValue) url += "&user_id=" + selectedUserValue.value;
    return url;
});
const isFavorite = (favorite_users) => {
    const d = favorite_users.find(
        (user) => user.id === page.props.auth.user.id
    );
    return d !== null && typeof d === "object" ? true : false;
};
watch(selectedUser, (selectedUser) => {
    form.user_id = selectedUser.value;
    form.get(route("posts.index"));
});
</script>

<template>
    <Head title="Posts" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex">
                <h2
                    class="flex-1 text-xl font-semibold leading-tight text-gray-800"
                >
                    Posts
                </h2>
                <Link
                    :href="route('posts.create')"
                    class="shadow-md bg-white rounded-xl px-4 mx-4"
                    >新規登録</Link
                >
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <Combobox v-model="selectedUser" by="label">
                        <ComboboxAnchor as-child>
                            <ComboboxTrigger as-child>
                                <Button
                                    variant="outline"
                                    class="justify-between"
                                >
                                    {{ selectedUser?.label ?? "Select user" }}

                                    <ChevronsUpDown
                                        class="ml-2 h-4 w-4 shrink-0 opacity-50"
                                    />
                                </Button>
                            </ComboboxTrigger>
                        </ComboboxAnchor>

                        <ComboboxList>
                            <div class="relative w-full max-w-sm items-center">
                                <ComboboxInput
                                    class="pl-9 focus-visible:ring-0 border-0 border-b rounded-none h-10"
                                    placeholder="Select user..."
                                />
                                <span
                                    class="absolute start-0 inset-y-0 flex items-center justify-center px-3"
                                >
                                    <Search
                                        class="size-4 text-muted-foreground"
                                    />
                                </span>
                            </div>

                            <ComboboxEmpty> No selected user. </ComboboxEmpty>

                            <ComboboxGroup>
                                <ComboboxItem
                                    v-for="user in usersList"
                                    :key="user.value"
                                    :value="user"
                                >
                                    {{ user.label }}

                                    <ComboboxItemIndicator>
                                        <Check :class="cn('ml-auto h-4 w-4')" />
                                    </ComboboxItemIndicator>
                                </ComboboxItem>
                            </ComboboxGroup>
                        </ComboboxList>
                    </Combobox>
                    <Table>
                        <TableCaption>ブログ一覧</TableCaption>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[200px]">
                                    タイトル
                                </TableHead>
                                <TableHead class="w-[100px] text-center">
                                    お気に入り
                                </TableHead>
                                <TableHead class="w-[140px]"> 作者 </TableHead>
                                <TableHead>抜粋</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="post in posts.data" :key="post.id">
                                <TableCell
                                    class="text-xm font-semibold text-indigo-600 focus:text-indigo-800"
                                    ><Link
                                        :href="route('posts.show', post.id)"
                                        >{{ post.title }}</Link
                                    ></TableCell
                                >
                                <TableCell
                                    v-if="isFavorite(post.favorite_users)"
                                    ><Star
                                        fill="yellow"
                                        strokeWidth="{1}"
                                        class="m-auto"
                                /></TableCell>
                                <TableCell v-else
                                    ><Star
                                        fill="white"
                                        strokeWidth="{1}"
                                        class="m-auto"
                                /></TableCell>
                                <TableCell>{{ post.user.name }}</TableCell>
                                <TableCell>{{ post.excerpt }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <Pagination
                        v-slot="{ page }"
                        :items-per-page="posts.per_page"
                        :total="posts.total"
                        :page="posts.current_page"
                    >
                        <PaginationContent v-slot="{ items }">
                            <Link :href="nextPreviousLink(posts.prev_page_url)">
                                <PaginationPrevious />
                            </Link>
                            <template
                                v-for="(item, index) in items"
                                :key="index"
                            >
                                <PaginationItem
                                    v-if="item.type === 'page'"
                                    :value="item.value"
                                    :is-active="item.value === page"
                                >
                                    <Link :href="paginationLink(item)"
                                        >{{ item.value }}
                                    </Link>
                                </PaginationItem>
                            </template>

                            <PaginationEllipsis :index="4" />
                            <Link :href="nextPreviousLink(posts.next_page_url)">
                                <PaginationNext />
                            </Link>
                        </PaginationContent>
                    </Pagination>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
