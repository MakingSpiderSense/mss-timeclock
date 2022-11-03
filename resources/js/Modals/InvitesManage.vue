<!-- Template -->
<template>
    <div>
        <div class="mb-6">Manage your organization invites below. Accepting an invite to an organization will give you access to the organization and its categories.</div>
        <!-- Invites -->
        <div v-if="invites.length > 0" class="invites">
            <!-- Invite -->
            <div class="invite" v-for="organization in invites" :key="organization">
                <div class="inv-org">{{ organization.name }}</div>
                <div class="inv-choice">
                    <button class="btn btn-success" @click="acceptInvite(organization.id)">Accept</button>
                    <button class="btn btn-danger" @click="declineInvite(organization.id)">Decline</button>
                </div>
            </div>
        </div>
        <!-- No Invites -->
        <div v-else class="no-invites">
            <div class="mb-4 font-bold">You have no invites at this time.</div>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
export default {
    name: 'InvitesManage',
    data: function() {
        return {
            invites: [],
        }
    },
    mounted() {
        this.loadInvites();
    },
    methods:{
        // Load invites
        loadInvites() {
            axios.get(route('organizations.invite-list')).then(res=>{
                if (res.status==200) {
                    this.invites = res.data;
                }
            }).catch(err=>{
                console.log(err);
            });
        },
        // Accept invite
        acceptInvite(organization) {
            axios.post(route('organizations.invite-accept', {'org_id': organization})).then(res=>{
                if (res.status==200) {
                    this.loadInvites();
                }
            }).catch(err=>{
                console.log(err);
            });
        },
        // Decline invite
        declineInvite(organization) {
            axios.post(route('organizations.invite-decline', {'org_id': organization})).then(res=>{
                if (res.status==200) {
                    this.loadInvites();
                }
            }).catch(err=>{
                console.log(err);
            });
        },
    }
}
</script>

<!-- Styling -->
<style lang="scss" scoped>
.invite {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 12px;
    &:hover {
        background-color: #f5f5f5;
    }
    .inv-org {
        font-weight: 600;
    }
    .inv-choice {
        display: flex;
        button {
            margin-left: 10px;
            padding: 5px 20px;
        }
    }
}
</style>