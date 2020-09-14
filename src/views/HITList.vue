<template>
    <div class="relative lg:w-5/6 px-8 pt-6 pb-8 flex flex-col mt-4 mx-auto">
        <h1 class="text-2xl mb-4 text-orange-400">Hit del progetto "nomeProgetto"</h1>
        <tableHIT :datiHIT="datiHIT" />
    </div>
</template>
<script>
import tableHIT from '../components/tableHIT.vue'
export default {
    name: 'HITList',
    components: {
        tableHIT,
    },
    data() {
        return {
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
                    NumberOfAssignmentsPending: 123,
                    NumberOfAssignmentsAvailable: 123,
                    NumberOfAssignmentsCompleted: 123,
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
                    NumberOfAssignmentsPending: 123,
                    NumberOfAssignmentsAvailable: 123,
                    NumberOfAssignmentsCompleted: 123,
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
                    NumberOfAssignmentsPending: 123,
                    NumberOfAssignmentsAvailable: 123,
                    NumberOfAssignmentsCompleted: 123,
                    selected: false,
                },
            ],
        }
    },
}
</script>

<style scoped></style>
