<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
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
const d = defineProps({
    posts: {
        type: Object,
    },
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
                    <Table>
                        <TableCaption>ブログ一覧</TableCaption>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[200px]">
                                    タイトル
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
                            <Link
                                :href="
                                    posts.prev_page_url
                                        ? posts.prev_page_url
                                        : undefined
                                "
                            >
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
                                    <Link
                                        :href="
                                            posts.links.find(
                                                (element) =>
                                                    element.page === item.value
                                            ).url
                                        "
                                        >{{ item.value }}</Link
                                    >
                                </PaginationItem>
                            </template>

                            <PaginationEllipsis :index="4" />
                            <Link
                                :href="
                                    posts.next_page_url
                                        ? posts.next_page_url
                                        : undefined
                                "
                            >
                                <PaginationNext />
                            </Link>
                        </PaginationContent>
                    </Pagination>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
