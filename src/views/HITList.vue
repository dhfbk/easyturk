<template>
    <div class="relative lg:w-5/6 pt-2 pb-8 flex flex-col mt-4 mx-2 xs2:mx-4 lg:mx-auto">
        <div v-if="loading"></div>
        <div v-else>
            <h1 class="text-2xl mb-4 text-primary">HIT for the project {{ $route.params.projectId }}</h1>
            <!--<tableHIT :datiHIT="datiHIT" />-->
            <dotMatrixHIT :tmpData="progressData" />
        </div>
    </div>
</template>
<script>
//import tableHIT from '../components/tableHIT.vue'
import dotMatrixHIT from '../components/dotMatrixHIT.vue'
import axios from 'axios'
export default {
    name: 'HITList',
    components: {
        //tableHIT,
        dotMatrixHIT,
    },
    data() {
        return {
            id: '',
            loading: true,
            progressData: [],
            datiHIT: [
                {
                    HITId: '76f8gf7g68cd',
                    HITTypeId: 'string',
                    HITGroupId: 'string',
                    HITLayoutId: 'string',
                    CreationTime: '2015-01-01',
                    Title: 'string',
                    Description: 'string',
                    Question: 'string',
                    Keywords: 'string',
                    HITStatus: 'Assignable',
                    MaxAssignments: 123,
                    Reward: 'string',
                    AutoApprovalDelayInSeconds: 123,
                    Expiration: '2015-01-01',
                    AssignmentDurationInSeconds: 123,
                    HITReviewStatus: 'NotReviewed',
                    NumberOfAssignmentsSubmitted: 123,
                    NumberOfAssignmentsApproved: 123,
                    NumberOfAssignmentsRejected: 123,
                    selected: false,
                },
                {
                    HITId: 'stri454g5g5kghng',
                    HITTypeId: 'string',
                    HITGroupId: 'string',
                    HITLayoutId: 'string',
                    CreationTime: '2015-01-01',
                    Title: 'string',
                    Description: 'string',
                    Question: 'string',
                    Keywords: 'string',
                    HITStatus: 'Reviewable',
                    MaxAssignments: 123,
                    Reward: 'string',
                    AutoApprovalDelayInSeconds: 123,
                    Expiration: '2015-01-01',
                    AssignmentDurationInSeconds: 123,
                    HITReviewStatus: 'NotReviewed',
                    NumberOfAssignmentsSubmitted: 123,
                    NumberOfAssignmentsApproved: 123,
                    NumberOfAssignmentsRejected: 123,
                    selected: false,
                },
                {
                    HITId: '3j747587d4k8',
                    HITTypeId: 'string',
                    HITGroupId: 'string',
                    HITLayoutId: 'string',
                    CreationTime: '2015-01-01',
                    Title: 'string',
                    Description: 'string',
                    Question: 'string',
                    Keywords: 'string',
                    HITStatus: 'Disposed',
                    MaxAssignments: 123,
                    Reward: 'string',
                    AutoApprovalDelayInSeconds: 123,
                    Expiration: '2015-01-01',
                    AssignmentDurationInSeconds: 123,
                    HITReviewStatus: 'ReviewedInappropriate',
                    NumberOfAssignmentsSubmitted: 123,
                    NumberOfAssignmentsApproved: 123,
                    NumberOfAssignmentsRejected: 123,
                    selected: false,
                },
            ],
        }
    },
    created() {
        this.getData()
    },
    methods: {
        getData() {
            this.id = this.$route.params.projectId
            axios
                .get(this.APIURL + '?action=getProjectInfo&id=' + this.id)
                .then(res => {
                    this.progressData = res.data.summary
                    this.loading = false
                })
                .catch(err => {
                    console.log(err)
                })
        },
    },
}
</script>

<style scoped></style>
