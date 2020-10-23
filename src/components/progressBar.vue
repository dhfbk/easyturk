<template>
    <div class="flex flex-col my-4 ">
        <div class="flex flex-row mx-auto w-11/12 ">
            <span class="tooltip relative" :style="{ width: submitted + '%' }">
                <div
                    class="h-8 bg-green-500 transition duration-200 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                    :class="[published == 0 ? 'rounded-l' : '', notPublished == 0 ? 'rounded-r' : '']"
                ></div>
                <span
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light regular"
                >
                    {{ progressData.hits_submitted }}/{{ progressData.hits_total }}
                </span>
            </span>
            <span class="tooltip relative" :style="{ width: published + '%' }">
                <div
                    class="h-8 bg-blue-500 transition duration-200 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                    :class="[
                        notPublished + published >= 99 ? 'rounded-l' : '',
                        notPublished == 0 && submitted == 0 ? 'rounded-r' : '',
                    ]"
                ></div>
                <span
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light regular"
                >
                    {{ progressData.hits_inserted - progressData.hits_submitted }}/{{ progressData.hits_total }}
                </span>
            </span>
            <span class="tooltip relative" :style="{ width: notPublished + '%' }">
                <div
                    class="h-8 bg-gray-500 transition duration-200 ease-in-out transform hover:-translate-y-1 hover:shadow-lg"
                    :class="notPublished > 0 ? 'rounded-r' : ''"
                ></div>
                <span
                    class="tooltip-text bg-gray-900 absolute rounded whitespace-no-wrap max-w-48 text-gray-100 text-sm font-light regular"
                >
                    {{ progressData.hits_total - progressData.hits_inserted }}/{{ progressData.hits_total }}
                </span>
            </span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        progressData: Object,
    },
    data() {
        return {
            notPublished: 0,
            published: 0,
            submitted: 0,
        }
    },
    created() {
        this.notPublished =
            ((parseInt(this.progressData.hits_total) - parseInt(this.progressData.hits_inserted)) /
                parseInt(this.progressData.hits_total)) *
            100
        this.submitted = (parseInt(this.progressData.hits_submitted) / parseInt(this.progressData.hits_total)) * 100
        if (this.progressData.hits_submitted != this.progressData.hits_inserted) {
            this.published =
                ((parseInt(this.progressData.hits_inserted) - parseInt(this.progressData.hits_submitted)) /
                    parseInt(this.progressData.hits_total)) *
                100
        }
    },
}
</script>

<style></style>
